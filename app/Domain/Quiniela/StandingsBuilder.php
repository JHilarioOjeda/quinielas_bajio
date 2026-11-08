<?php

namespace App\Domain\Quiniela;

use App\Models\QuinielaEvent;

class StandingsBuilder
{
    /**
     * Arma las filas de la tabla de resultados (predicciones, aciertos,
     * desempate) para una jornada ya cargada con sus relaciones
     * (eventMatches.matchGame.*, tickets.ticketMatches.matchGame,
     * tickets.ticketMatches.predictions).
     *
     * @return array{regularMatches: \Illuminate\Support\Collection, substituteMatch: mixed, matchResults: array, displayMap: array, rows: array, firstTiebreakerApplied: bool, secondTiebreakerApplied: bool, subResultAvailable: bool}
     */
    public static function build(QuinielaEvent $quiniela): array
    {
        $regularMatches = $quiniela->eventMatches->where('is_substitute', false)->values();
        $substituteMatch = $quiniela->eventMatches->firstWhere('is_substitute', true);

        $resultsByMatchId = TicketScorer::resultsByMatchId($quiniela->eventMatches);

        // La vista indexa por id de event_match, no por match_id
        $matchResults = $quiniela->eventMatches
            ->mapWithKeys(fn ($em) => [$em->id => $resultsByMatchId[$em->match_id] ?? null])
            ->all();

        $displayMap = ['1' => 'L', 'X' => 'E', '2' => 'V'];

        $rows = [];
        foreach ($quiniela->tickets as $ticket) {
            $predByMatchId = [];
            foreach ($ticket->ticketMatches as $tm) {
                $predByMatchId[$tm->match_id] = $tm->predictions->pluck('selection')->toArray();
            }

            $combinations = [[]];
            foreach ($quiniela->eventMatches as $eventMatch) {
                $selections = array_values(array_unique($predByMatchId[$eventMatch->match_id] ?? []));
                $selections = $selections ?: [null];
                $expanded = [];
                foreach ($combinations as $combination) {
                    foreach ($selections as $selection) {
                        $expanded[] = $combination + [$eventMatch->id => $selection];
                    }
                }
                $combinations = $expanded;
            }

            foreach ($combinations as $index => $combination) {
                $predictions = [];
                $points = 0;
                foreach ($regularMatches as $eventMatch) {
                    $selection = $combination[$eventMatch->id] ?? null;
                    $predictions[$eventMatch->id] = $selection ? [$displayMap[$selection] ?? $selection] : [];
                    if ($selection !== null && $selection === ($resultsByMatchId[$eventMatch->match_id] ?? null)) {
                        $points++;
                    }
                }
                $subSelection = $substituteMatch ? ($combination[$substituteMatch->id] ?? null) : null;
                $subResult = $substituteMatch ? ($resultsByMatchId[$substituteMatch->match_id] ?? null) : null;

                $rows[] = [
                    'ticket_id' => $ticket->id,
                    'folio' => $ticket->folio,
                    'player_name' => $ticket->player_name,
                    'phone_number' => $ticket->phone_number,
                    'entry_number' => $index + 1,
                    'entry_total' => count($combinations),
                    'predictions' => $predictions,
                    'sub_prediction' => $subSelection ? [$displayMap[$subSelection] ?? $subSelection] : [],
                    'sub_hit' => $subSelection !== null && $subSelection === $subResult,
                    'points' => $points,
                ];
            }
        }

        usort($rows, function ($a, $b) {
            if ($b['points'] !== $a['points']) {
                return $b['points'] <=> $a['points'];
            }
            if ($b['sub_hit'] !== $a['sub_hit']) {
                return $b['sub_hit'] <=> $a['sub_hit'];
            }
            return strcmp($a['player_name'], $b['player_name']) ?: ($a['ticket_id'] <=> $b['ticket_id']) ?: ($a['entry_number'] <=> $b['entry_number']);
        });

        $subResultAvailable = $substituteMatch && ($resultsByMatchId[$substituteMatch->match_id] ?? null) !== null;

        $standings = TiebreakResolver::resolve(collect($rows), 'points', 'sub_hit', $subResultAvailable);

        return [
            'regularMatches'          => $regularMatches,
            'substituteMatch'         => $substituteMatch,
            'matchResults'            => $matchResults,
            'displayMap'              => $displayMap,
            'rows'                    => $rows,
            'firstTiebreakerApplied'  => $standings['firstTiebreakerApplied'],
            'secondTiebreakerApplied' => $standings['secondTiebreakerApplied'],
            'subResultAvailable'      => $subResultAvailable,
            'firstPlace'              => $standings['firstPlace'],
            'secondPlace'             => $standings['secondPlace'],
        ];
    }
}

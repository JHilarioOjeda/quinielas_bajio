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
        $regularMatchIds = $regularMatches->pluck('match_id')->all();

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

            $predictions = [];
            foreach ($regularMatches as $em) {
                $sels = $predByMatchId[$em->match_id] ?? [];
                $predictions[$em->id] = array_map(fn ($s) => $displayMap[$s] ?? $s, $sels);
            }

            $subPrediction = [];
            if ($substituteMatch) {
                $sels = $predByMatchId[$substituteMatch->match_id] ?? [];
                $subPrediction = array_map(fn ($s) => $displayMap[$s] ?? $s, $sels);
            }

            $rows[] = [
                'folio'          => $ticket->folio,
                'player_name'    => $ticket->player_name,
                'phone_number'   => $ticket->phone_number,
                'predictions'    => $predictions,
                'sub_prediction' => $subPrediction,
                'sub_hit'        => TicketScorer::substituteHit($ticket, $substituteMatch?->match_id, $resultsByMatchId),
                'points'         => TicketScorer::hits($ticket, $regularMatchIds, $resultsByMatchId),
            ];
        }

        usort($rows, function ($a, $b) {
            if ($b['points'] !== $a['points']) {
                return $b['points'] <=> $a['points'];
            }
            if ($b['sub_hit'] !== $a['sub_hit']) {
                return $b['sub_hit'] <=> $a['sub_hit'];
            }
            return strcmp($a['player_name'], $b['player_name']);
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
        ];
    }
}

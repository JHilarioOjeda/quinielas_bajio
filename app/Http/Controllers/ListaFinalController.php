<?php

namespace App\Http\Controllers;

use App\Models\QuinielaEvent;

class ListaFinalController extends Controller
{
    public function show()
    {
        $quiniela = QuinielaEvent::with([
            'eventMatches' => fn ($q) => $q->orderBy('is_substitute')->orderBy('id'),
            'eventMatches.matchGame.homeTeam',
            'eventMatches.matchGame.awayTeam',
            'tickets' => fn ($q) => $q->where('payment_status', 'pagado')->where('active', true),
            'tickets.ticketMatches.matchGame',
            'tickets.ticketMatches.predictions',
        ])->first();

        abort_unless($quiniela, 404);

        // Separar partidos regulares y suplente
        $regularMatches = $quiniela->eventMatches->where('is_substitute', false)->values();
        $substituteMatch = $quiniela->eventMatches->firstWhere('is_substitute', true);

        // Calcular resultado real de cada partido (1=L, X=E, 2=V, null=pendiente)
        $matchResults = [];
        foreach ($quiniela->eventMatches as $em) {
            $mg = $em->matchGame;
            if ($mg && $mg->home_score !== null && $mg->away_score !== null) {
                if ($mg->home_score > $mg->away_score) {
                    $matchResults[$em->id] = '1'; // Local
                } elseif ($mg->home_score == $mg->away_score) {
                    $matchResults[$em->id] = 'X'; // Empate
                } else {
                    $matchResults[$em->id] = '2'; // Visitante
                }
            } else {
                $matchResults[$em->id] = null;
            }
        }

        // Mapeo de valor DB → letra de display
        $displayMap = ['1' => 'L', 'X' => 'E', '2' => 'V'];

        // Construir filas de participantes
        $rows = [];
        foreach ($quiniela->tickets as $ticket) {
            // Indexar predicciones por match_id
            $predByMatchId = [];
            foreach ($ticket->ticketMatches as $tm) {
                $sels = $tm->predictions->pluck('selection')->toArray();
                $predByMatchId[$tm->match_id] = $sels;
            }

            // Predicciones por event_match id
            $predictions = [];
            $points = 0;

            foreach ($regularMatches as $em) {
                $sels = $predByMatchId[$em->match_id] ?? [];
                $displaySels = array_map(fn($s) => $displayMap[$s] ?? $s, $sels);
                $predictions[$em->id] = $displaySels;

                // Sumar punto si el resultado está disponible y cualquier selección coincide
                $result = $matchResults[$em->id] ?? null;
                if ($result && in_array($result, $sels, true)) {
                    $points++;
                }
            }

            // Predicción del partido suplente + si acertó
            $subPrediction = [];
            $subHit        = false;
            if ($substituteMatch) {
                $sels          = $predByMatchId[$substituteMatch->match_id] ?? [];
                $subPrediction = array_map(fn($s) => $displayMap[$s] ?? $s, $sels);
                $subResult     = $matchResults[$substituteMatch->id] ?? null;
                if ($subResult && in_array($subResult, $sels, true)) {
                    $subHit = true;
                }
            }

            $rows[] = [
                'folio'          => $ticket->folio,
                'player_name'    => $ticket->player_name,
                'phone_number'   => $ticket->phone_number,
                'predictions'    => $predictions,
                'sub_prediction' => $subPrediction,
                'sub_hit'        => $subHit,
                'points'         => $points,
            ];
        }

        // Ordenar: puntos desc → sub_hit desc (diferenciador) → nombre asc
        usort($rows, function ($a, $b) {
            if ($b['points'] !== $a['points']) {
                return $b['points'] <=> $a['points'];
            }
            if ($b['sub_hit'] !== $a['sub_hit']) {
                return $b['sub_hit'] <=> $a['sub_hit'];
            }
            return strcmp($a['player_name'], $b['player_name']);
        });

        // Calcular si se aplica diferenciador para 1° y 2° lugar
        $maxPoints = $rows[0]['points'] ?? 0;
        $subResultAvailable = $substituteMatch && ($matchResults[$substituteMatch->id] ?? null) !== null;

        $tiedFirst  = collect($rows)->where('points', $maxPoints);
        $firstTiebreakerApplied = $subResultAvailable && $tiedFirst->count() > 20
                                    && $tiedFirst->where('sub_hit', true)->isNotEmpty();

        $secondPoints = collect($rows)->where('points', '<', $maxPoints)->max('points');
        $secondTiebreakerApplied = false;
        if ($secondPoints !== null && $secondPoints > 0) {
            $tiedSecond = collect($rows)->where('points', $secondPoints);
            $secondTiebreakerApplied = $subResultAvailable && $tiedSecond->count() > 20
                                        && $tiedSecond->where('sub_hit', true)->isNotEmpty();
        }

        return view('admin.lista-final', compact(
            'quiniela',
            'regularMatches',
            'substituteMatch',
            'matchResults',
            'displayMap',
            'rows',
            'firstTiebreakerApplied',
            'secondTiebreakerApplied',
            'subResultAvailable'
        ));
    }
}

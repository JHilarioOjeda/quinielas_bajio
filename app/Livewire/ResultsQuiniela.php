<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\QuinielaEvent;
use App\Models\Ticket;
use App\Models\MatchGame;

class ResultsQuiniela extends Component
{
    public function render()
    {
        $quiniela = QuinielaEvent::with([
            'eventMatches.matchGame.homeTeam',
            'eventMatches.matchGame.awayTeam',
        ])->first();

        $tickets = collect();
        $firstPlaceTickets = collect();
        $secondPlaceTickets = collect();
        $zeroHitsTickets = collect();
        $firstTiebreakerApplied = false;
        $secondTiebreakerApplied = false;

        if ($quiniela) {
            // Partidos regulares (sin suplente)
            $regularMatchIds = $quiniela->eventMatches
                ->where('is_substitute', false)
                ->pluck('match_id')
                ->all();

            // Partido suplente y su resultado
            $substituteEventMatch = $quiniela->eventMatches->firstWhere('is_substitute', true);
            $substituteMatchId    = $substituteEventMatch?->match_id;
            $substituteResult     = null;

            if ($substituteEventMatch?->matchGame) {
                $mg = $substituteEventMatch->matchGame;
                if ($mg->home_score !== null && $mg->away_score !== null) {
                    $substituteResult = $this->getResultCode($mg);
                }
            }

            $tickets = Ticket::with([
                'ticketMatches.matchGame',
                'ticketMatches.predictions',
            ])->where('quiniela_event_id', $quiniela->id)
                ->where('payment_status', 'pagado')
                ->where('active', true)
                ->get();

            foreach ($tickets as $ticket) {
                $ticket->hits    = $this->calculateTicketHits($ticket, $regularMatchIds);
                $ticket->sub_hit = $this->calculateSubstituteHit($ticket, $substituteMatchId, $substituteResult);
            }

            if ($tickets->isNotEmpty()) {
                $maxHits = $tickets->max('hits');

                if ($maxHits !== null && $maxHits > 0) {
                    $firstPlaceAll = $tickets->where('hits', $maxHits);

                    // Aplicar diferenciador si hay más de 20 ganadores y el suplente tiene resultado
                    if ($firstPlaceAll->count() > 20 && $substituteResult !== null) {
                        $withSubHit = $firstPlaceAll->where('sub_hit', true);
                        if ($withSubHit->isNotEmpty()) {
                            $firstPlaceTickets        = $withSubHit->values();
                            $firstTiebreakerApplied   = true;
                        } else {
                            $firstPlaceTickets = $firstPlaceAll->values();
                        }
                    } else {
                        $firstPlaceTickets = $firstPlaceAll->values();
                    }

                    $secondHits = $tickets
                        ->where('hits', '<', $maxHits)
                        ->max('hits');

                    if ($secondHits !== null && $secondHits > 0) {
                        $secondPlaceAll = $tickets->where('hits', $secondHits);

                        if ($secondPlaceAll->count() > 20 && $substituteResult !== null) {
                            $withSubHit = $secondPlaceAll->where('sub_hit', true);
                            if ($withSubHit->isNotEmpty()) {
                                $secondPlaceTickets       = $withSubHit->values();
                                $secondTiebreakerApplied  = true;
                            } else {
                                $secondPlaceTickets = $secondPlaceAll->values();
                            }
                        } else {
                            $secondPlaceTickets = $secondPlaceAll->values();
                        }
                    }
                }

                $zeroHitsTickets = $tickets->where('hits', 0)->values();
            }
        }

        return view('livewire.results-quiniela', [
            'quiniela'                 => $quiniela,
            'tickets'                  => $tickets,
            'firstPlaceTickets'        => $firstPlaceTickets,
            'secondPlaceTickets'       => $secondPlaceTickets,
            'zeroHitsTickets'          => $zeroHitsTickets,
            'firstTiebreakerApplied'   => $firstTiebreakerApplied,
            'secondTiebreakerApplied'  => $secondTiebreakerApplied,
        ]);
    }

    protected function calculateTicketHits(Ticket $ticket, array $regularMatchIds): int
    {
        $hits = 0;

        foreach ($ticket->ticketMatches as $ticketMatch) {
            if (! in_array($ticketMatch->match_id, $regularMatchIds, true)) {
                continue;
            }

            $match = $ticketMatch->matchGame;

            if (! $match || is_null($match->home_score) || is_null($match->away_score)) {
                continue;
            }

            $resultCode  = $this->getResultCode($match);
            $predictions = $ticketMatch->predictions->pluck('selection')->all();

            if ($resultCode && in_array($resultCode, $predictions, true)) {
                $hits++;
            }
        }

        return $hits;
    }

    protected function calculateSubstituteHit(Ticket $ticket, ?int $substituteMatchId, ?string $substituteResult): bool
    {
        if ($substituteMatchId === null || $substituteResult === null) {
            return false;
        }

        foreach ($ticket->ticketMatches as $ticketMatch) {
            if ($ticketMatch->match_id !== $substituteMatchId) {
                continue;
            }

            $predictions = $ticketMatch->predictions->pluck('selection')->all();
            return in_array($substituteResult, $predictions, true);
        }

        return false;
    }

    protected function getResultCode(MatchGame $match): ?string
    {
        if (is_null($match->home_score) || is_null($match->away_score)) {
            return null;
        }

        if ($match->home_score > $match->away_score) {
            return '1';
        }

        if ($match->home_score < $match->away_score) {
            return '2';
        }

        return 'X';
    }
}

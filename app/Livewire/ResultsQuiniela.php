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

        if ($quiniela) {
                        $tickets = Ticket::with([
                                'ticketMatches.matchGame',
                                'ticketMatches.predictions',
                        ])->where('quiniela_event_id', $quiniela->id)
                            ->where('payment_status', 'pagado')
                            ->where('active', true)
                            ->get();

            foreach ($tickets as $ticket) {
                $ticket->hits = $this->calculateTicketHits($ticket);
            }

            if ($tickets->isNotEmpty()) {
                $maxHits = $tickets->max('hits');

                if ($maxHits !== null && $maxHits > 0) {
                    $firstPlaceTickets = $tickets->where('hits', $maxHits)->values();

                    $secondHits = $tickets
                        ->where('hits', '<', $maxHits)
                        ->max('hits');

                    if ($secondHits !== null && $secondHits > 0) {
                        $secondPlaceTickets = $tickets->where('hits', $secondHits)->values();
                    }
                }

                $zeroHitsTickets = $tickets->where('hits', 0)->values();
            }
        }

        return view('livewire.results-quiniela', [
            'quiniela' => $quiniela,
            'tickets' => $tickets,
            'firstPlaceTickets' => $firstPlaceTickets,
            'secondPlaceTickets' => $secondPlaceTickets,
            'zeroHitsTickets' => $zeroHitsTickets,
        ]);
    }

    protected function calculateTicketHits(Ticket $ticket): int
    {
        $hits = 0;

        foreach ($ticket->ticketMatches as $ticketMatch) {
            $match = $ticketMatch->matchGame;

            if (! $match) {
                continue;
            }

            if (is_null($match->home_score) || is_null($match->away_score)) {
                continue; // partido sin resultado aún
            }

            $resultCode = $this->getResultCode($match);

            if (! $resultCode) {
                continue;
            }

            $predictions = $ticketMatch->predictions->pluck('selection')->all();

            if (in_array($resultCode, $predictions, true)) {
                $hits++;
            }
        }

        return $hits;
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

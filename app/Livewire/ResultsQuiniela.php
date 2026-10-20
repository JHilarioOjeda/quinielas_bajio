<?php

namespace App\Livewire;

use App\Domain\Quiniela\TicketScorer;
use App\Domain\Quiniela\TiebreakResolver;
use Livewire\Component;
use App\Models\QuinielaEvent;
use App\Models\Ticket;

class ResultsQuiniela extends Component
{
    public function render()
    {
        $quiniela = QuinielaEvent::current()->with([
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

            $resultsByMatchId = TicketScorer::resultsByMatchId($quiniela->eventMatches);
            $substituteResult = $substituteMatchId ? ($resultsByMatchId[$substituteMatchId] ?? null) : null;

            $tickets = Ticket::with([
                'ticketMatches.matchGame',
                'ticketMatches.predictions',
            ])->where('quiniela_event_id', $quiniela->id)
                ->where('payment_status', Ticket::PAYMENT_PAID)
                ->where('active', true)
                ->get();

            foreach ($tickets as $ticket) {
                $ticket->hits    = TicketScorer::hits($ticket, $regularMatchIds, $resultsByMatchId);
                $ticket->sub_hit = TicketScorer::substituteHit($ticket, $substituteMatchId, $resultsByMatchId);
            }

            if ($tickets->isNotEmpty()) {
                $standings = TiebreakResolver::resolve($tickets, 'hits', 'sub_hit', $substituteResult !== null);

                $firstPlaceTickets        = $standings['firstPlace'];
                $firstTiebreakerApplied   = $standings['firstTiebreakerApplied'];
                $secondPlaceTickets       = $standings['secondPlace'];
                $secondTiebreakerApplied  = $standings['secondTiebreakerApplied'];

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
}

<?php

namespace App\Livewire;

use App\Domain\Quiniela\StandingsBuilder;
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
            $tickets = Ticket::with([
                'ticketMatches.matchGame',
                'ticketMatches.predictions',
            ])->where('quiniela_event_id', $quiniela->id)
                ->where('payment_status', Ticket::PAYMENT_PAID)
                ->where('active', true)
                ->get();

            if ($tickets->isNotEmpty()) {
                $quiniela->setRelation('tickets', $tickets);
                $standings = StandingsBuilder::build($quiniela);
                $toEntry = fn ($row) => (object) ($row + ['hits' => $row['points']]);
                $firstPlaceTickets = $standings['firstPlace']->map($toEntry);
                $secondPlaceTickets = $standings['secondPlace']->map($toEntry);
                $firstTiebreakerApplied = $standings['firstTiebreakerApplied'];
                $secondTiebreakerApplied = $standings['secondTiebreakerApplied'];
                $zeroHitsTickets = collect($standings['rows'])->where('points', 0)->map($toEntry)->values();
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

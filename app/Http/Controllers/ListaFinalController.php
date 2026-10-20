<?php

namespace App\Http\Controllers;

use App\Domain\Quiniela\StandingsBuilder;
use App\Models\QuinielaEvent;
use App\Models\Ticket;

class ListaFinalController extends Controller
{
    public function show()
    {
        $quiniela = QuinielaEvent::current()->with([
            'eventMatches' => fn ($q) => $q->orderBy('is_substitute')->orderBy('id'),
            'eventMatches.matchGame.homeTeam',
            'eventMatches.matchGame.awayTeam',
            'tickets' => fn ($q) => $q->where('payment_status', Ticket::PAYMENT_PAID)->where('active', true),
            'tickets.ticketMatches.matchGame',
            'tickets.ticketMatches.predictions',
        ])->first();

        abort_unless($quiniela, 404);

        return view('admin.lista-final', ['quiniela' => $quiniela] + StandingsBuilder::build($quiniela));
    }
}

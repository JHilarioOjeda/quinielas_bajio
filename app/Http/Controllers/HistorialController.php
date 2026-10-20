<?php

namespace App\Http\Controllers;

use App\Domain\Quiniela\StandingsBuilder;
use App\Models\QuinielaEvent;
use App\Models\Ticket;

class HistorialController extends Controller
{
    public function index()
    {
        $jornadas = QuinielaEvent::where('status', 'finished')
            ->withCount(['tickets as tickets_pagados' => fn ($q) => $q->where('payment_status', Ticket::PAYMENT_PAID)])
            ->withSum(['tickets as recaudo' => fn ($q) => $q->where('payment_status', Ticket::PAYMENT_PAID)], 'amount_paid')
            ->orderByDesc('play_start')
            ->get();

        return view('admin.historial-index', compact('jornadas'));
    }

    public function show(QuinielaEvent $quiniela)
    {
        abort_unless($quiniela->status === 'finished', 404);

        $quiniela->load([
            'eventMatches' => fn ($q) => $q->orderBy('is_substitute')->orderBy('id'),
            'eventMatches.matchGame.homeTeam',
            'eventMatches.matchGame.awayTeam',
            // Una jornada archivada ya tiene todos sus tickets en active=false,
            // asi que aqui solo se filtra por pago, no por active.
            'tickets' => fn ($q) => $q->where('payment_status', Ticket::PAYMENT_PAID),
            'tickets.ticketMatches.matchGame',
            'tickets.ticketMatches.predictions',
        ]);

        return view('admin.lista-final', [
            'quiniela' => $quiniela,
            'historialBack' => route('admin.historial.index'),
        ] + StandingsBuilder::build($quiniela));
    }
}

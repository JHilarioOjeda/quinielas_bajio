<?php

namespace App\Http\Controllers;

use App\Domain\Quiniela\StandingsBuilder;
use App\Models\QuinielaEvent;
use App\Models\Ticket;
use App\Services\MatchResultsVersion;
use Illuminate\Http\JsonResponse;

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

        return view('admin.lista-final', [
            'quiniela' => $quiniela,
            'resultsVersion' => app(MatchResultsVersion::class)->forEvent($quiniela),
        ] + StandingsBuilder::build($quiniela));
    }

    /** Comprueba cambios sin cargar ni recalcular boletos o posiciones. */
    public function resultsVersion(MatchResultsVersion $version): JsonResponse
    {
        $quiniela = QuinielaEvent::current()->with(['eventMatches.matchGame'])->first();

        return response()->json(['version' => $version->forEvent($quiniela)])
            ->header('Cache-Control', 'no-store, private');
    }
}

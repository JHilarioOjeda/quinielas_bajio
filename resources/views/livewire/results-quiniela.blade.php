<div class="w-full bg-primarycolor min-h-screen p-2">
    <div class="max-w-5xl mx-auto bg-white rounded-xl border border-primaryhcolor shadow-sm p-3 md:p-6 space-y-4 md:space-y-6">
        @if(!$quiniela)
            <p class="text-center text-sm text-gray-600">No hay una quiniela activa para mostrar resultados.</p>
        @else
            <!-- Encabezado -->
            <div class="flex md:flex-row md:items-center md:justify-between gap-2">
                <div class="w-3/4">
                    <p class="text-xs uppercase tracking-wide text-amber-600">Resultados</p>
                    <h1 class="text-lg md:text-2xl font-semibold text-primarycolor">
                        {{ $quiniela->title }}
                    </h1>
                    <p class="text-[11px] md:text-xs text-gray-600">
                        Jornada del
                        {{ $quiniela->play_start ? \Carbon\Carbon::parse($quiniela->play_start)->locale('es')->translatedFormat('d F Y') : '' }}
                        al
                        {{ $quiniela->play_end ? \Carbon\Carbon::parse($quiniela->play_end)->locale('es')->translatedFormat('d F Y') : '' }}
                    </p>
                </div>
                <div class="flex items-center">
                    <img
                    src="/imgs/logos/logoquinielas.png"
                    alt="Logo Quinielas"
                    class="md:w-28 w-20 object-contain"
                />
                </div>
                <!-- <div class="text-xs md:text-sm text-gray-500">
                    <p>Precio por jugada: <span class="font-semibold text-primarycolor">{{ '$ ' . ($quiniela->price ?? 'N/A') }}</span></p>
                </div> -->
            </div>

            @php
                $eventMatches = $quiniela->eventMatches ?? collect();
                $regularMatches = $eventMatches->where('is_substitute', false);
                $substituteMatches = $eventMatches->where('is_substitute', true);
            @endphp

            <!-- Resultados de partidos -->
            <div class="space-y-3">
                <h2 class="text-sm md:text-base font-semibold text-gray-800">Resultados de los partidos</h2>

                <div class="-mx-2 md:mx-0 overflow-x-auto">
                    <div class="min-w-[360px] md:min-w-0 px-2 md:px-0">
                        <div class="grid grid-cols-[minmax(0,1.5fr)_minmax(0,1fr)_minmax(0,1.5fr)_80px] px-2 py-2 rounded-t bg-amber-50 border border-amber-200 text-[11px] md:text-xs font-semibold text-amber-700">
                            <div class="text-left">LOCAL</div>
                            <div class="text-center">MARCADOR</div>
                            <div class="text-right">VISITANTE</div>
                            <div class="text-center">RESULTADO</div>
                        </div>

                        <div class="divide-y divide-gray-200 border-x border-b border-gray-200 overflow-hidden">
                            @foreach($regularMatches as $match)
                                @php
                                    $matchGame = $match->matchGame;
                                    $homeTeam = $matchGame?->homeTeam;
                                    $awayTeam = $matchGame?->awayTeam;
                                    $homeScore = $matchGame?->home_score;
                                    $awayScore = $matchGame?->away_score;

                                    $resultLabel = 'Pendiente';
                                    $resultBadge = 'bg-gray-200 text-gray-700';

                                    if (!is_null($homeScore) && !is_null($awayScore)) {
                                        if ($homeScore > $awayScore) {
                                            $resultLabel = 'Local';
                                            $resultBadge = 'bg-emerald-100 text-emerald-700';
                                        } elseif ($homeScore < $awayScore) {
                                            $resultLabel = 'Visitante';
                                            $resultBadge = 'bg-blue-100 text-blue-700';
                                        } else {
                                            $resultLabel = 'Empate';
                                            $resultBadge = 'bg-amber-100 text-amber-700';
                                        }
                                    }
                                @endphp

                                <div class="bg-white px-2 py-2 text-[11px] md:text-sm" wire:key="result-match-{{ $match->id }}">
                                    <div class="grid grid-cols-[minmax(0,1.5fr)_minmax(0,1fr)_minmax(0,1.5fr)_80px] items-center gap-2">
                                        <div class="flex items-center gap-2 min-w-0">
                                            @if($homeTeam?->logo)
                                                <img class="size-6 md:size-7 object-contain" src="{{ Storage::url($homeTeam->logo) }}" alt="{{ $homeTeam->name }}" />
                                            @endif
                                            <span class="font-semibold text-gray-900 truncate">{{ $homeTeam?->name ?? 'Local' }}</span>
                                        </div>

                                        <div class="text-center font-semibold text-gray-800">
                                            @if(!is_null($homeScore) && !is_null($awayScore))
                                                {{ $homeScore }} - {{ $awayScore }}
                                            @else
                                                <span class="text-[10px] text-gray-400">Pendiente</span>
                                            @endif
                                        </div>

                                        <div class="flex items-center gap-2 min-w-0 justify-end">
                                            <span class="font-semibold text-gray-900 truncate">{{ $awayTeam?->name ?? 'Visitante' }}</span>
                                            @if($awayTeam?->logo)
                                                <img class="size-6 md:size-7 object-contain" src="{{ Storage::url($awayTeam->logo) }}" alt="{{ $awayTeam->name }}" />
                                            @endif
                                        </div>

                                        <div class="flex justify-center">
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] md:text-xs font-semibold {{ $resultBadge }}">
                                                {{ $resultLabel }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                @if($substituteMatches->isNotEmpty())
                    <div class="mt-3">
                        <p class="text-[11px] md:text-xs font-semibold text-amber-700 mb-1">Partido suplente</p>
                        <div class="-mx-2 md:mx-0 overflow-x-auto">
                            <div class="min-w-[360px] md:min-w-0 px-2 md:px-0">
                                <div class="divide-y divide-amber-200 border border-amber-200 rounded-lg overflow-hidden">
                                    @foreach($substituteMatches as $match)
                                        @php
                                            $matchGame = $match->matchGame;
                                            $homeTeam = $matchGame?->homeTeam;
                                            $awayTeam = $matchGame?->awayTeam;
                                            $homeScore = $matchGame?->home_score;
                                            $awayScore = $matchGame?->away_score;

                                            $resultLabel = 'Pendiente';
                                            $resultBadge = 'bg-gray-200 text-gray-700';

                                            if (!is_null($homeScore) && !is_null($awayScore)) {
                                                if ($homeScore > $awayScore) {
                                                    $resultLabel = 'Local';
                                                    $resultBadge = 'bg-emerald-100 text-emerald-700';
                                                } elseif ($homeScore < $awayScore) {
                                                    $resultLabel = 'Visitante';
                                                    $resultBadge = 'bg-blue-100 text-blue-700';
                                                } else {
                                                    $resultLabel = 'Empate';
                                                    $resultBadge = 'bg-amber-100 text-amber-700';
                                                }
                                            }
                                        @endphp

                                        <div class="bg-amber-50 px-2 py-2 text-[11px] md:text-sm" wire:key="result-sub-match-{{ $match->id }}">
                                            <div class="grid grid-cols-[minmax(0,1.5fr)_minmax(0,1fr)_minmax(0,1.5fr)_80px] items-center gap-2">
                                                <div class="flex items-center gap-2 min-w-0">
                                                    @if($homeTeam?->logo)
                                                        <img class="size-6 md:size-7 object-contain" src="{{ Storage::url($homeTeam->logo) }}" alt="{{ $homeTeam->name }}" />
                                                    @endif
                                                    <span class="font-semibold text-gray-900 truncate">{{ $homeTeam?->name ?? 'Local' }}</span>
                                                </div>

                                                <div class="text-center font-semibold text-gray-800">
                                                    @if(!is_null($homeScore) && !is_null($awayScore))
                                                        {{ $homeScore }} - {{ $awayScore }}
                                                    @else
                                                        <span class="text-[10px] text-gray-500">Pendiente</span>
                                                    @endif
                                                </div>

                                                <div class="flex items-center gap-2 min-w-0 justify-end">
                                                    <span class="font-semibold text-gray-900 truncate">{{ $awayTeam?->name ?? 'Visitante' }}</span>
                                                    @if($awayTeam?->logo)
                                                        <img class="size-6 md:size-7 object-contain" src="{{ Storage::url($awayTeam->logo) }}" alt="{{ $awayTeam->name }}" />
                                                    @endif
                                                </div>

                                                <div class="flex justify-center">
                                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] md:text-xs font-semibold {{ $resultBadge }}">
                                                        {{ $resultLabel }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Resumen de ganadores -->
            <div class="pt-3 md:pt-4 border-t border-gray-200 space-y-3">
                <h2 class="text-sm md:text-base font-semibold text-gray-800">Resultados de jugadores</h2>

                @if($tickets->isEmpty())
                    <p class="text-[11px] md:text-sm text-gray-500">Aún no hay tickets registrados para esta quiniela.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 md:gap-4">
                        <!-- Primer lugar -->
                        <div class="border border-emerald-300 bg-emerald-50 rounded-lg p-3 flex flex-col text-[11px] md:text-sm">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="font-semibold text-emerald-800 text-xs md:text-sm">Primer lugar</h3>
                                @php
                                    $firstHits = $firstPlaceTickets->isNotEmpty() ? $firstPlaceTickets->first()->hits : null;
                                @endphp
                                @if($firstHits !== null)
                                    <span class="px-2 py-0.5 rounded-full bg-white text-emerald-700 text-[10px] md:text-xs font-semibold border border-emerald-200">
                                        {{ $firstHits }} aciertos
                                    </span>
                                @endif
                            </div>

                            @if($firstPlaceTickets->isEmpty())
                                <p class="text-[11px] md:text-xs text-emerald-700/80">Aún no hay tickets con aciertos.</p>
                            @else
                                <ul class="space-y-1">
                                    @foreach($firstPlaceTickets as $ticket)
                                        <li class="flex items-center justify-between">
                                            <div class="min-w-0 pr-2">
                                                <p class="font-semibold text-emerald-900 truncate">{{ $ticket->player_name }}</p>
                                                <p class="text-[10px] md:text-[11px] text-emerald-700/80">Folio: <span class="font-mono">{{ $ticket->folio }}</span></p>
                                            </div>
                                            <span class="text-[10px] md:text-xs text-emerald-800 font-semibold">{{ $ticket->hits }} ✔</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <!-- Segundo lugar -->
                        <div class="border border-blue-300 bg-blue-50 rounded-lg p-3 flex flex-col text-[11px] md:text-sm">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="font-semibold text-blue-800 text-xs md:text-sm">Segundo lugar</h3>
                                @php
                                    $secondHits = $secondPlaceTickets->isNotEmpty() ? $secondPlaceTickets->first()->hits : null;
                                @endphp
                                @if($secondHits !== null)
                                    <span class="px-2 py-0.5 rounded-full bg-white text-blue-700 text-[10px] md:text-xs font-semibold border border-blue-200">
                                        {{ $secondHits }} aciertos
                                    </span>
                                @endif
                            </div>

                            @if($secondPlaceTickets->isEmpty())
                                <p class="text-[11px] md:text-xs text-blue-700/80">Aún no hay tickets con segundo mejor puntaje.</p>
                            @else
                                <ul class="space-y-1">
                                    @foreach($secondPlaceTickets as $ticket)
                                        <li class="flex items-center justify-between">
                                            <div class="min-w-0 pr-2">
                                                <p class="font-semibold text-blue-900 truncate">{{ $ticket->player_name }}</p>
                                                <p class="text-[10px] md:text-[11px] text-blue-700/80">Folio: <span class="font-mono">{{ $ticket->folio }}</span></p>
                                            </div>
                                            <span class="text-[10px] md:text-xs text-blue-800 font-semibold">{{ $ticket->hits }} ✔</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <!-- Cero aciertos -->
                        <div class="border border-gray-300 bg-gray-50 rounded-lg p-3 flex flex-col text-[11px] md:text-sm">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="font-semibold text-gray-800 text-xs md:text-sm">0 aciertos</h3>
                                <span class="px-2 py-0.5 rounded-full bg-white text-gray-700 text-[10px] md:text-xs font-semibold border border-gray-200">
                                    @if($zeroHitsTickets->isNotEmpty())
                                        {{ $zeroHitsTickets->count() }} jugador(es)
                                    @else
                                        ---
                                    @endif
                                </span>
                            </div>

                            @if($zeroHitsTickets->isEmpty())
                                <p class="text-[11px] md:text-xs text-gray-600">Nadie terminó con 0 aciertos en esta quiniela.</p>
                            @else
                                <ul class="space-y-1">
                                    @foreach($zeroHitsTickets as $ticket)
                                        <li class="flex items-center justify-between">
                                            <div class="min-w-0 pr-2">
                                                <p class="font-semibold text-gray-900 truncate">{{ $ticket->player_name }}</p>
                                                <p class="text-[10px] md:text-[11px] text-gray-600">Folio: <span class="font-mono">{{ $ticket->folio }}</span></p>
                                            </div>
                                            <span class="text-[10px] md:text-xs text-gray-500 font-semibold">0 ✖</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>

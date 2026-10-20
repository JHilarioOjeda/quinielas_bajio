<div class="w-full min-h-screen bg-primarycolor p-3 md:p-6">
    <div class="max-w-5xl mx-auto bg-white rounded-xl border border-primaryhcolor shadow-sm p-4 md:p-6 space-y-5">
        @if(!$quiniela)
            <p class="text-center text-base text-gray-600">No hay una quiniela activa para mostrar resultados.</p>
        @else
            <!-- Encabezado -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-4 pb-4 border-b border-gray-200">
                <img
                    src="/imgs/logos/logoquinielas.png"
                    alt="Logo Quinielas"
                    class="w-20 sm:w-24 object-contain mx-auto sm:mx-0"
                />
                <div class="flex-1 text-center sm:text-left">
                    <p class="text-sm font-semibold uppercase tracking-wide text-amber-600">Resultados</p>
                    <h1 class="text-xl sm:text-2xl font-bold text-primarycolor leading-tight">
                        {{ $quiniela->title }}
                    </h1>
                    <p class="text-base text-gray-600">
                        Jornada del
                        {{ $quiniela->play_start ? \Carbon\Carbon::parse($quiniela->play_start)->locale('es')->translatedFormat('d F Y') : '' }}
                        al
                        {{ $quiniela->play_end ? \Carbon\Carbon::parse($quiniela->play_end)->locale('es')->translatedFormat('d F Y') : '' }}
                    </p>
                </div>
            </div>

            @php
                $eventMatches = $quiniela->eventMatches ?? collect();
                $regularMatches = $eventMatches->where('is_substitute', false);
                $substituteMatches = $eventMatches->where('is_substitute', true);
            @endphp

            <!-- Resultados de partidos -->
            <div class="space-y-3">
                <h2 class="text-lg font-semibold text-gray-800">Resultados de los partidos</h2>

                <!-- Tarjetas (móvil) -->
                <div class="space-y-2 sm:hidden">
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

                        <div class="rounded-lg border border-gray-200 p-3" wire:key="result-match-m-{{ $match->id }}">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2 min-w-0 flex-1">
                                    @if($homeTeam?->logo)
                                        <img class="size-7 object-contain flex-shrink-0" src="{{ Storage::url($homeTeam->logo) }}" alt="{{ $homeTeam->name }}" />
                                    @endif
                                    <span class="font-semibold text-gray-900 text-base truncate">{{ $homeTeam?->name ?? 'Local' }}</span>
                                </div>
                                <span class="font-bold text-gray-800 text-lg flex-shrink-0 px-2">
                                    @if(!is_null($homeScore) && !is_null($awayScore))
                                        {{ $homeScore }} - {{ $awayScore }}
                                    @else
                                        <span class="text-sm text-gray-400 font-normal">vs</span>
                                    @endif
                                </span>
                                <div class="flex items-center gap-2 min-w-0 flex-1 justify-end">
                                    <span class="font-semibold text-gray-900 text-base truncate">{{ $awayTeam?->name ?? 'Visitante' }}</span>
                                    @if($awayTeam?->logo)
                                        <img class="size-7 object-contain flex-shrink-0" src="{{ Storage::url($awayTeam->logo) }}" alt="{{ $awayTeam->name }}" />
                                    @endif
                                </div>
                            </div>
                            <div class="mt-2 flex justify-center">
                                <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold {{ $resultBadge }}">
                                    {{ $resultLabel }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Tabla (escritorio) -->
                <div class="hidden sm:block rounded-lg border border-gray-200 overflow-hidden">
                    <div class="grid grid-cols-[minmax(0,1.5fr)_minmax(0,1fr)_minmax(0,1.5fr)_100px] px-3 py-2 bg-amber-50 border-b border-amber-200 text-sm font-semibold text-amber-700">
                        <div class="text-left">LOCAL</div>
                        <div class="text-center">MARCADOR</div>
                        <div class="text-right">VISITANTE</div>
                        <div class="text-center">RESULTADO</div>
                    </div>

                    <div class="divide-y divide-gray-200">
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

                            <div class="bg-white px-3 py-3 text-base" wire:key="result-match-{{ $match->id }}">
                                <div class="grid grid-cols-[minmax(0,1.5fr)_minmax(0,1fr)_minmax(0,1.5fr)_100px] items-center gap-2">
                                    <div class="flex items-center gap-2 min-w-0">
                                        @if($homeTeam?->logo)
                                            <img class="size-8 object-contain flex-shrink-0" src="{{ Storage::url($homeTeam->logo) }}" alt="{{ $homeTeam->name }}" />
                                        @endif
                                        <span class="font-semibold text-gray-900 truncate">{{ $homeTeam?->name ?? 'Local' }}</span>
                                    </div>

                                    <div class="text-center font-semibold text-gray-800">
                                        @if(!is_null($homeScore) && !is_null($awayScore))
                                            {{ $homeScore }} - {{ $awayScore }}
                                        @else
                                            <span class="text-sm text-gray-400 font-normal">Pendiente</span>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-2 min-w-0 justify-end">
                                        <span class="font-semibold text-gray-900 truncate">{{ $awayTeam?->name ?? 'Visitante' }}</span>
                                        @if($awayTeam?->logo)
                                            <img class="size-8 object-contain flex-shrink-0" src="{{ Storage::url($awayTeam->logo) }}" alt="{{ $awayTeam->name }}" />
                                        @endif
                                    </div>

                                    <div class="flex justify-center">
                                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold {{ $resultBadge }}">
                                            {{ $resultLabel }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if($substituteMatches->isNotEmpty())
                    <div class="mt-3">
                        <p class="text-sm font-semibold text-amber-700 mb-1">Partido suplente</p>

                        <!-- Tarjetas (móvil) -->
                        <div class="space-y-2 sm:hidden">
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

                                <div class="rounded-lg border border-amber-200 bg-amber-50 p-3" wire:key="result-sub-match-m-{{ $match->id }}">
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-2 min-w-0 flex-1">
                                            @if($homeTeam?->logo)
                                                <img class="size-7 object-contain flex-shrink-0" src="{{ Storage::url($homeTeam->logo) }}" alt="{{ $homeTeam->name }}" />
                                            @endif
                                            <span class="font-semibold text-gray-900 text-base truncate">{{ $homeTeam?->name ?? 'Local' }}</span>
                                        </div>
                                        <span class="font-bold text-gray-800 text-lg flex-shrink-0 px-2">
                                            @if(!is_null($homeScore) && !is_null($awayScore))
                                                {{ $homeScore }} - {{ $awayScore }}
                                            @else
                                                <span class="text-sm text-gray-500 font-normal">vs</span>
                                            @endif
                                        </span>
                                        <div class="flex items-center gap-2 min-w-0 flex-1 justify-end">
                                            <span class="font-semibold text-gray-900 text-base truncate">{{ $awayTeam?->name ?? 'Visitante' }}</span>
                                            @if($awayTeam?->logo)
                                                <img class="size-7 object-contain flex-shrink-0" src="{{ Storage::url($awayTeam->logo) }}" alt="{{ $awayTeam->name }}" />
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mt-2 flex justify-center">
                                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold {{ $resultBadge }}">
                                            {{ $resultLabel }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Tabla (escritorio) -->
                        <div class="hidden sm:block rounded-lg border border-amber-200 overflow-hidden">
                            <div class="divide-y divide-amber-200">
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

                                    <div class="bg-amber-50 px-3 py-3 text-base" wire:key="result-sub-match-{{ $match->id }}">
                                        <div class="grid grid-cols-[minmax(0,1.5fr)_minmax(0,1fr)_minmax(0,1.5fr)_100px] items-center gap-2">
                                            <div class="flex items-center gap-2 min-w-0">
                                                @if($homeTeam?->logo)
                                                    <img class="size-8 object-contain flex-shrink-0" src="{{ Storage::url($homeTeam->logo) }}" alt="{{ $homeTeam->name }}" />
                                                @endif
                                                <span class="font-semibold text-gray-900 truncate">{{ $homeTeam?->name ?? 'Local' }}</span>
                                            </div>

                                            <div class="text-center font-semibold text-gray-800">
                                                @if(!is_null($homeScore) && !is_null($awayScore))
                                                    {{ $homeScore }} - {{ $awayScore }}
                                                @else
                                                    <span class="text-sm text-gray-500 font-normal">Pendiente</span>
                                                @endif
                                            </div>

                                            <div class="flex items-center gap-2 min-w-0 justify-end">
                                                <span class="font-semibold text-gray-900 truncate">{{ $awayTeam?->name ?? 'Visitante' }}</span>
                                                @if($awayTeam?->logo)
                                                    <img class="size-8 object-contain flex-shrink-0" src="{{ Storage::url($awayTeam->logo) }}" alt="{{ $awayTeam->name }}" />
                                                @endif
                                            </div>

                                            <div class="flex justify-center">
                                                <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold {{ $resultBadge }}">
                                                    {{ $resultLabel }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Resumen de ganadores -->
            <div class="pt-4 border-t border-gray-200 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <h2 class="text-lg font-semibold text-gray-800">Resultados de jugadores</h2>

                    <x-link-primary link="{{ route('admin.lista-final') }}" target="" class="w-full sm:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 flex-shrink-0">
                            <path fill-rule="evenodd" d="M7.502 6h7.128A3.375 3.375 0 0 1 18 9.375v9.375a3 3 0 0 0 3-3V6.108c0-1.505-1.125-2.811-2.664-2.94a48.972 48.972 0 0 0-.673-.05A3 3 0 0 0 15 1.5h-1.5a3 3 0 0 0-2.663 1.618c-.225.015-.45.032-.673.05C8.662 3.295 7.554 4.542 7.502 6ZM13.5 3A1.5 1.5 0 0 0 12 4.5h4.5A1.5 1.5 0 0 0 15 3h-1.5Z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M3 9.375C3 8.339 3.84 7.5 4.875 7.5h9.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 0 1 3 20.625V9.375ZM6 12a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V12Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75ZM6 15a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V15Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75ZM6 18a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V18Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                        </svg>
                        Lista Final
                    </x-link-primary>
                </div>

                @if($tickets->isEmpty())
                    <p class="text-base text-gray-500">Aún no hay tickets registrados para esta quiniela.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Primer lugar -->
                        <div class="border border-emerald-300 bg-emerald-50 rounded-lg p-4 flex flex-col text-base">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="font-semibold text-emerald-800 text-base">Primer lugar</h3>
                                @php
                                    $firstHits = $firstPlaceTickets->isNotEmpty() ? $firstPlaceTickets->first()->hits : null;
                                @endphp
                                @if($firstHits !== null)
                                    <span class="px-2 py-1 rounded-full bg-white text-emerald-700 text-sm font-semibold border border-emerald-200">
                                        {{ $firstHits }} aciertos
                                    </span>
                                @endif
                            </div>

                            @if($firstTiebreakerApplied)
                                <p class="text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded px-2 py-1.5 mb-2 leading-snug">
                                    ★ Diferenciador aplicado: se usó el partido suplente para reducir ganadores.
                                </p>
                            @endif

                            @if($firstPlaceTickets->isEmpty())
                                <p class="text-sm text-emerald-700/80">Aún no hay tickets con aciertos.</p>
                            @else
                                <ul class="space-y-2">
                                    @foreach($firstPlaceTickets as $ticket)
                                        <li class="flex items-center justify-between">
                                            <div class="min-w-0 pr-2">
                                                <p class="font-semibold text-emerald-900 truncate">{{ $ticket->player_name }}</p>
                                                <p class="text-sm text-emerald-700/80">Folio: <span class="font-mono">{{ $ticket->folio }}</span></p>
                                            </div>
                                            <span class="text-sm text-emerald-800 font-semibold flex-shrink-0">{{ $ticket->hits }} ✔</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <!-- Segundo lugar -->
                        <div class="border border-blue-300 bg-blue-50 rounded-lg p-4 flex flex-col text-base">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="font-semibold text-blue-800 text-base">Segundo lugar</h3>
                                @php
                                    $secondHits = $secondPlaceTickets->isNotEmpty() ? $secondPlaceTickets->first()->hits : null;
                                @endphp
                                @if($secondHits !== null)
                                    <span class="px-2 py-1 rounded-full bg-white text-blue-700 text-sm font-semibold border border-blue-200">
                                        {{ $secondHits }} aciertos
                                    </span>
                                @endif
                            </div>

                            @if($secondTiebreakerApplied)
                                <p class="text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded px-2 py-1.5 mb-2 leading-snug">
                                    ★ Diferenciador aplicado: se usó el partido suplente para reducir ganadores.
                                </p>
                            @endif

                            @if($secondPlaceTickets->isEmpty())
                                <p class="text-sm text-blue-700/80">Aún no hay tickets con segundo mejor puntaje.</p>
                            @else
                                <ul class="space-y-2">
                                    @foreach($secondPlaceTickets as $ticket)
                                        <li class="flex items-center justify-between">
                                            <div class="min-w-0 pr-2">
                                                <p class="font-semibold text-blue-900 truncate">{{ $ticket->player_name }}</p>
                                                <p class="text-sm text-blue-700/80">Folio: <span class="font-mono">{{ $ticket->folio }}</span></p>
                                            </div>
                                            <span class="text-sm text-blue-800 font-semibold flex-shrink-0">{{ $ticket->hits }} ✔</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <!-- Cero aciertos -->
                        <div class="border border-gray-300 bg-gray-50 rounded-lg p-4 flex flex-col text-base">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="font-semibold text-gray-800 text-base">0 aciertos</h3>
                                <span class="px-2 py-1 rounded-full bg-white text-gray-700 text-sm font-semibold border border-gray-200">
                                    @if($zeroHitsTickets->isNotEmpty())
                                        {{ $zeroHitsTickets->count() }} jugador(es)
                                    @else
                                        ---
                                    @endif
                                </span>
                            </div>

                            @if($zeroHitsTickets->isEmpty())
                                <p class="text-sm text-gray-600">Nadie terminó con 0 aciertos en esta quiniela.</p>
                            @else
                                <ul class="space-y-2">
                                    @foreach($zeroHitsTickets as $ticket)
                                        <li class="flex items-center justify-between">
                                            <div class="min-w-0 pr-2">
                                                <p class="font-semibold text-gray-900 truncate">{{ $ticket->player_name }}</p>
                                                <p class="text-sm text-gray-600">Folio: <span class="font-mono">{{ $ticket->folio }}</span></p>
                                            </div>
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

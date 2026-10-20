<div>
    <div class="w-full min-h-screen bg-primarycolor p-3 md:p-6">
        <div class="max-w-4xl mx-auto bg-white rounded-xl border border-primaryhcolor shadow-sm p-4 md:p-6 space-y-4">

            <!-- Encabezado -->
            <div class="flex items-center gap-3 pb-3 border-b border-gray-200">
                <img
                    src="/imgs/logos/logoquinielas.png"
                    alt="Logo Quinielas"
                    class="w-12 sm:w-14 object-contain flex-shrink-0"
                />

                <div class="flex-1 min-w-0">
                    <h1 class="text-base sm:text-lg font-bold text-primarycolor leading-tight truncate">
                        {{ $quiniela->title ?? '' }}
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-500">
                        Precio por jugada: <span class="font-semibold text-amber-600">{{ '$ ' . ($quiniela->price ?? 'N/A') }}</span>
                    </p>
                </div>

                <div class="flex items-center gap-1 flex-shrink-0">
                    <button
                        type="button"
                        wire:click="toggleRulesModal"
                        title="Reglamento"
                        class="inline-flex items-center justify-center min-h-9 min-w-9 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-amber-600"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                            <path fill-rule="evenodd"
                                d="M4.125 3C3.089 3 2.25 3.84 2.25 4.875V18a3 3 0 0 0 3 3h15a3 3 0 0 1-3-3V4.875C17.25 3.839 16.41 3 15.375 3H4.125Z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <button
                        type="button"
                        wire:click="toggleWhatsModal"
                        title="WhatsApp"
                        class="inline-flex items-center justify-center min-h-9 min-w-9 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-emerald-600"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                            <path d="M12 2C6.486 2 2 6.201 2 11.294c0 1.747.478 3.393 1.385 4.86L2 22l6.05-1.93C9.43 20.54 10.705 20.79 12 20.79 17.514 20.79 22 16.586 22 11.494 22 6.401 17.514 2 12 2Zm0 2c4.087 0 7.4 3.127 7.4 7.494 0 4.368-3.313 7.495-7.4 7.495-1.13 0-2.246-.235-3.26-.692l-.233-.106-3.57 1.139 1.16-3.18-.151-.244C4.365 15.19 4 13.98 4 11.294 4 7.127 7.223 4 12 4Zm-3.09 3.75a.7.7 0 0 0-.52.246c-.27.314-.71.868-.77 1.47-.09.924.28 2.042 1.28 3.222 1 1.18 2.52 2.542 4.83 3.25.83.26 1.48.083 2-.27.42-.287.69-.74.78-1.07.08-.33.08-.61.05-.67a.35.35 0 0 0-.19-.22l-1.79-.83a.5.5 0 0 0-.49.04l-.71.54c-.08.06-.23.09-.36.05-.38-.12-1.64-.66-2.34-1.77-.18-.28-.22-.43-.16-.57l.52-.7a.47.47 0 0 0 .06-.47l-.78-1.83a.44.44 0 0 0-.33-.27 1.86 1.86 0 0 0-.37-.03Z" />
                        </svg>
                    </button>
                </div>
            </div>

            @if($quiniela?->play_start || $quiniela?->sales_end)
                <div class="text-xs sm:text-sm text-gray-500 flex flex-wrap gap-x-4 gap-y-0.5">
                    @if($quiniela?->play_start)
                        <p>
                            Partidos:
                            {{ \Carbon\Carbon::parse($quiniela->play_start)->locale('es')->translatedFormat('d F') }}
                            al
                            {{ $quiniela?->play_end ? \Carbon\Carbon::parse($quiniela->play_end)->locale('es')->translatedFormat('d F Y') : '' }}
                        </p>
                    @endif
                    @if($quiniela?->sales_end)
                        <p>
                            Ventas hasta: {{ \Carbon\Carbon::parse($quiniela->sales_end)->locale('es')->translatedFormat('d F, H:i') }}
                        </p>
                    @endif
                </div>
            @endif

            @if(! $quiniela || $quiniela->status !== 'open')
                @if($quiniela?->status === 'closed')
                    <div class="bg-amber-50 border border-amber-200 p-6 flex flex-col items-center justify-center rounded-lg min-h-[50vh]">
                        <p class="text-2xl font-bold text-yellow-700 uppercase text-center">
                            Ventas cerradas
                        </p>
                        <p class="text-gray-700 text-center text-lg my-6">Da clic en el siguiente botón para ir a los resultados en tiempo real y ver la lista final.</p>
                        <x-button-primary type="button" class="justify-center" onclick="window.location='{{ route('quiniela.results') }}'">
                            Ver resultados
                        </x-button-primary>
                    </div>
                @else
                    <div class="bg-amber-50 border border-amber-200 p-6 flex flex-col items-center justify-center rounded-lg min-h-[50vh]">
                        <p class="text-2xl font-bold text-yellow-700 uppercase text-center">
                            No hay quiniela disponible
                        </p>
                        <p class="text-gray-700 text-center text-lg my-6">Gracias por tu preferencia. Espera la nueva quiniela.</p>
                    </div>
                @endif
            @else
            <div class="w-full">

                @php
                    $eventMatches = $quiniela->eventMatches ?? collect();
                    $regularMatches = $eventMatches->where('is_substitute', false);
                    $substituteMatches = $eventMatches->where('is_substitute', true);
                @endphp

                <!-- Encabezado de columnas -->
                <div class="grid grid-cols-[minmax(0,1fr)_44px_minmax(0,1fr)] sm:grid-cols-[minmax(0,1fr)_72px_minmax(0,1fr)] gap-1.5 sm:gap-2 px-2 py-1.5 rounded-t-lg bg-amber-50 border border-amber-200 text-[11px] sm:text-sm font-semibold text-amber-700">
                    <div class="text-right sm:text-left">LOCAL</div>
                    <div class="text-center">EMPATE</div>
                    <div class="text-left sm:text-right">VISITANTE</div>
                </div>

                <div class="divide-y divide-gray-200 border-x border-b border-gray-200 rounded-b-lg overflow-hidden mb-4">
                    @foreach ($regularMatches as $match)
                        @php
                            $matchGame = $match->matchGame;
                            $homeTeam = $matchGame?->homeTeam;
                            $awayTeam = $matchGame?->awayTeam;
                        @endphp

                        <div class="bg-white px-2 py-2" wire:key="event-match-{{ $match->id }}">
                            <div class="grid grid-cols-[minmax(0,1fr)_44px_minmax(0,1fr)] sm:grid-cols-[minmax(0,1fr)_72px_minmax(0,1fr)] items-center gap-1.5 sm:gap-2">
                                <label class="flex items-center justify-between gap-1.5 sm:gap-2 cursor-pointer min-w-0">
                                    <span class="flex items-center justify-end gap-1.5 sm:gap-2 min-w-0 flex-1">
                                        <span class="font-semibold text-gray-900 text-xs sm:text-base leading-tight text-right break-words">{{ $homeTeam?->name ?? 'Local' }}</span>
                                        @if($homeTeam?->logo)
                                            <img class="size-6 sm:size-8 object-contain flex-shrink-0" src="{{ Storage::url($homeTeam->logo) }}" alt="{{ $homeTeam->name }}" />
                                        @endif
                                    </span>
                                    <input type="checkbox" class="checksquiniela !size-6 sm:!size-8 flex-shrink-0" wire:model.live="picks.{{ $match->id }}.H" />
                                </label>

                                <div class="flex items-center justify-center">
                                    <input type="checkbox" class="checksquiniela !size-6 sm:!size-8" wire:model.live="picks.{{ $match->id }}.D" />
                                </div>

                                <label class="flex items-center gap-1.5 sm:gap-2 cursor-pointer min-w-0">
                                    <input type="checkbox" class="checksquiniela !size-6 sm:!size-8 flex-shrink-0" wire:model.live="picks.{{ $match->id }}.A" />
                                    <span class="flex items-center gap-1.5 sm:gap-2 min-w-0 flex-1">
                                        @if($awayTeam?->logo)
                                            <img class="size-6 sm:size-8 object-contain flex-shrink-0" src="{{ Storage::url($awayTeam->logo) }}" alt="{{ $awayTeam->name }}" />
                                        @endif
                                        <span class="font-semibold text-gray-900 text-xs sm:text-base leading-tight break-words">{{ $awayTeam?->name ?? 'Visitante' }}</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($substituteMatches->isNotEmpty())
                    <div class="mb-4">
                        <div class="px-3 py-2 bg-amber-50 border border-amber-200 rounded-t-lg">
                            <p class="text-sm font-semibold text-amber-700">Partido suplente</p>
                            <p class="text-[10px] text-gray-500 leading-snug">Solo cuenta si se suspende alguno de los partidos anteriores. <span class="font-bold">No olvides marcarlo.</span></p>
                        </div>

                        <div class="divide-y divide-amber-200 border-x border-b border-amber-200 rounded-b-lg overflow-hidden">
                            @foreach($substituteMatches as $match)
                                @php
                                    $matchGame = $match->matchGame;
                                    $homeTeam = $matchGame?->homeTeam;
                                    $awayTeam = $matchGame?->awayTeam;
                                @endphp

                                <div class="bg-amber-50 px-2 py-2" wire:key="event-match-substitute-{{ $match->id }}">
                                    <div class="grid grid-cols-[minmax(0,1fr)_44px_minmax(0,1fr)] sm:grid-cols-[minmax(0,1fr)_72px_minmax(0,1fr)] items-center gap-1.5 sm:gap-2">
                                        <label class="flex items-center justify-between gap-1.5 sm:gap-2 cursor-pointer min-w-0">
                                            <span class="flex items-center justify-end gap-1.5 sm:gap-2 min-w-0 flex-1">
                                                <span class="font-semibold text-gray-900 text-xs sm:text-base leading-tight text-right break-words">{{ $homeTeam?->name ?? 'Local' }}</span>
                                                @if($homeTeam?->logo)
                                                    <img class="size-6 sm:size-8 object-contain flex-shrink-0" src="{{ Storage::url($homeTeam->logo) }}" alt="{{ $homeTeam->name }}" />
                                                @endif
                                            </span>
                                            <input type="checkbox" class="checksquiniela !size-6 sm:!size-8 flex-shrink-0" wire:model.live="picks.{{ $match->id }}.H" />
                                        </label>

                                        <div class="flex items-center justify-center">
                                            <input type="checkbox" class="checksquiniela !size-6 sm:!size-8" wire:model.live="picks.{{ $match->id }}.D" />
                                        </div>

                                        <label class="flex items-center gap-1.5 sm:gap-2 cursor-pointer min-w-0">
                                            <input type="checkbox" class="checksquiniela !size-6 sm:!size-8 flex-shrink-0" wire:model.live="picks.{{ $match->id }}.A" />
                                            <span class="flex items-center gap-1.5 sm:gap-2 min-w-0 flex-1">
                                                @if($awayTeam?->logo)
                                                    <img class="size-6 sm:size-8 object-contain flex-shrink-0" src="{{ Storage::url($awayTeam->logo) }}" alt="{{ $awayTeam->name }}" />
                                                @endif
                                                <span class="font-semibold text-gray-900 text-xs sm:text-base leading-tight break-words">{{ $awayTeam?->name ?? 'Visitante' }}</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Resumen de jugadas con dobles/triples -->
                <div class="sticky bottom-2 z-10 bg-amber-50/95 backdrop-blur border border-amber-300 rounded-lg p-3 shadow-md flex items-center justify-between gap-3 mb-4">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-amber-700 uppercase tracking-wide">Lo que vas a jugar</p>
                        <p class="text-[11px] text-gray-500 leading-snug">1 marca = simple · 2 = doble · 3 = triple</p>
                    </div>
                    <div class="flex items-center gap-4 flex-shrink-0">
                        <div class="text-center">
                            <p class="text-[11px] text-gray-500 leading-none mb-0.5">Jugadas</p>
                            <p class="text-2xl font-bold text-amber-700 leading-none">{{ $totalCombinations }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-[11px] text-gray-500 leading-none mb-0.5">Total a pagar</p>
                            <p class="text-2xl font-bold text-emerald-700 leading-none">
                                {{ $totalCombinations > 0 && ($quiniela->price ?? 0) > 0 ? '$ ' . number_format($totalPrice, 2) : '$ 0.00' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Datos del jugador y guardado de ticket -->
                <div class="flex flex-col sm:flex-row sm:items-end gap-3">
                    <div class="flex-1">
                        <label class="block text-base font-semibold text-gray-700 mb-1">
                            Nombre completo
                        </label>
                        <input
                            type="text"
                            wire:model.live="player_name"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-base min-h-11 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500"
                            placeholder="Escribe tu nombre completo"
                        />
                        @error('player_name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-button-primary
                        type="button"
                        class="w-full sm:w-auto justify-center"
                        wire:click="saveTicket"
                    >
                        Guardar quiniela
                    </x-button-primary>
                </div>

                @if($last_ticket_folio)
                    <div id="ticket-summary" class="mt-4 w-full">
                        <div class="bg-emerald-50 border border-emerald-300 rounded-lg p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <p class="font-semibold text-lg text-emerald-700">{{$player_name ?? ''}}, tu quiniela se generó correctamente</p>
                                <p class="text-sm text-emerald-700">Conserva este folio como comprobante.</p>
                                <p class="text-sm text-emerald-700">Para jugar otra quiniela llena el formulario nuevamente.</p>
                            </div>
                            <div class="flex flex-col sm:items-end gap-1 flex-shrink-0">
                                <div class="flex items-center gap-1">
                                    <span class="text-emerald-700 text-sm">Folio:</span>
                                    <span class="font-mono font-bold text-emerald-800 text-xl">{{ $last_ticket_folio }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="text-emerald-700 text-sm">Total a pagar:</span>
                                    <span class="font-bold text-emerald-800 text-xl">$ {{ number_format($last_ticket_total, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 bg-emerald-50 border border-emerald-300 rounded-lg p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <p class="text-sm text-emerald-700">
                                Para confirmar tu jugada, envíala también por WhatsApp.
                            </p>
                            <a
                                href="https://wa.me/{{ $whatsNumber }}?text={{ $ticketWhatsText }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center justify-center gap-2 min-h-11 px-5 py-2.5 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold shadow w-full sm:w-auto flex-shrink-0"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 flex-shrink-0">
                                    <path d="M12 2C6.486 2 2 6.201 2 11.294c0 1.747.478 3.393 1.385 4.86L2 22l6.05-1.93C9.43 20.54 10.705 20.79 12 20.79 17.514 20.79 22 16.586 22 11.494 22 6.401 17.514 2 12 2Zm0 2c4.087 0 7.4 3.127 7.4 7.494 0 4.368-3.313 7.495-7.4 7.495-1.13 0-2.246-.235-3.26-.692l-.233-.106-3.57 1.139 1.16-3.18-.151-.244C4.365 15.19 4 13.98 4 11.294 4 7.127 7.223 4 12 4Zm-3.09 3.75a.7.7 0 0 0-.52.246c-.27.314-.71.868-.77 1.47-.09.924.28 2.042 1.28 3.222 1 1.18 2.52 2.542 4.83 3.25.83.26 1.48.083 2-.27.42-.287.69-.74.78-1.07.08-.33.08-.61.05-.67a.35.35 0 0 0-.19-.22l-1.79-.83a.5.5 0 0 0-.49.04l-.71.54c-.08.06-.23.09-.36.05-.38-.12-1.64-.66-2.34-1.77-.18-.28-.22-.43-.16-.57l.52-.7a.47.47 0 0 0 .06-.47l-.78-1.83a.44.44 0 0 0-.33-.27 1.86 1.86 0 0 0-.37-.03Z" />
                                </svg>
                                Enviar por WhatsApp
                            </a>
                        </div>
                    </div>
                @endif
            </div>
            @endif

        </div>
    </div>

    @if($showRulesModal)
    <div
        class="fixed inset-0 z-40 bg-black bg-opacity-50 flex items-center justify-center px-3"
        wire:click.self="toggleRulesModal"
    >
        <!-- Contenido modal -->
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[85vh] overflow-y-auto p-5 md:p-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <h2 class="text-xl font-bold text-secondarycolor">Reglamento de la quiniela</h2>
                <button
                    type="button"
                    aria-label="Cerrar"
                    class="flex-shrink-0 inline-flex items-center justify-center min-h-11 min-w-11 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600"
                    wire:click="toggleRulesModal"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="space-y-3 text-base text-gray-800 leading-relaxed">
                <p>
                    Si decides participar en nuestra quiniela estás aceptando estas reglas.
                    En caso de no estar de acuerdo con alguna de ellas favor de no participar.
                </p>

                <ul class="list-disc list-inside space-y-3">
                    <li>Solo participan mayores de 18 años.</li>
                    <li>El ganador o ganadores es el que tenga más aciertos al final de la jornada.</li>
                    <li>
                        <span class="font-semibold">EN CASO DE EMPATE SE REPARTE EN PARTES IGUALES.</span>
                        Para los segundos lugares en caso de ser más de 20 ganadores el partido suplente entra como comodín
                        para reducir la cantidad de ganadores y solo se repartirá premio si como mínimo les toca de 100 pesos a c/u;
                        de lo contrario el premio a segundo lugar se acumulará para la siguiente jornada.
                    </li>
                    <li>
                        TODOS LOS PARTIDOS SOLO CUENTAN EL RESULTADO DE LOS 90 MINUTOS MÁS EL AGREGADO,
                        NO CUENTA TIEMPO EXTRA NI PENALES.
                    </li>
                    <li>
                        Todas las semanas se avisa la hora que se publicará la prelista; en el lapso que es publicada es cuando deben
                        revisar si sus quinielas están agregadas correctamente o si hay algún error en sus quinielas.
                    </li>
                    <li>
                        Una vez publicada la lista final no se aceptan reclamos o quejas. En caso de que su quiniela no se haya capturado
                        se reembolsa el dinero que hayas depositado o se guardan las quinielas para la próxima semana.
                    </li>
                    <li>
                        En caso de que hubieras ganado con esas quinielas que por algún motivo no se hayan capturado, no se te entrega
                        ningún premio. Como se mencionó antes, se reembolsa lo que hayas depositado o se guarda para la próxima semana.
                        Por eso es muy importante revisarse en la prelista.
                    </li>
                    <li>
                        En caso de suspensión de un partido que se suspenda durante el encuentro y no se posponga para antes del último
                        partido de la quiniela, se toma el resultado que quedó al suspenderse el juego.
                    </li>
                    <li>
                        En caso de suspenderse un partido y se haya avisado con tiempo de anticipación, el administrador de la quiniela
                        avisará por el grupo si se sortea el partido o si se cambia por otro.
                    </li>
                    <li>
                        Premio para quinielas de 0 ACIERTOS: gana quién no realiza ningún punto de la jornada. El premio solo se reparte si
                        mínimo se gana la cantidad del costo de la quiniela (${{ $quiniela->price ?? '0' }}); de lo contrario se acumula para la siguiente jornada.
                    </li>
                </ul>

                <p class="text-sm text-gray-500 mt-2">
                    Te recomendamos leer cuidadosamente cada punto antes de participar.
                </p>
            </div>

            <div class="mt-5 flex justify-end">
                <x-button-primary type="button" class="w-full sm:w-auto justify-center" wire:click="toggleRulesModal">
                    Entendido
                </x-button-primary>
            </div>
        </div>
    </div>
@endif

@if($showWhatsModal)
    <div class="fixed inset-0 z-40 bg-black bg-opacity-50 flex items-center justify-center px-3" wire:click.self="toggleWhatsModal">
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[85vh] overflow-y-auto p-5 md:p-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <h2 class="text-xl font-bold text-emerald-600">Contacto de WhatsApp</h2>
                <button
                    type="button"
                    aria-label="Cerrar"
                    class="flex-shrink-0 inline-flex items-center justify-center min-h-11 min-w-11 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600"
                    wire:click="toggleWhatsModal"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="space-y-4 text-base text-gray-800 leading-relaxed flex flex-col">
                <p>Tienes alguna duda o quieres enviar tu pago por WhatsApp, escribe al siguiente número:</p>
                <a
                    href="https://wa.me/{{ $whatsNumber }}?text={{ $whatsText }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center justify-center gap-2 min-h-11 px-5 py-2.5 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white text-base font-semibold shadow mx-auto"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 flex-shrink-0">
                        <path d="M12 2C6.486 2 2 6.201 2 11.294c0 1.747.478 3.393 1.385 4.86L2 22l6.05-1.93C9.43 20.54 10.705 20.79 12 20.79 17.514 20.79 22 16.586 22 11.494 22 6.401 17.514 2 12 2Zm0 2c4.087 0 7.4 3.127 7.4 7.494 0 4.368-3.313 7.495-7.4 7.495-1.13 0-2.246-.235-3.26-.692l-.233-.106-3.57 1.139 1.16-3.18-.151-.244C4.365 15.19 4 13.98 4 11.294 4 7.127 7.223 4 12 4Zm-3.09 3.75a.7.7 0 0 0-.52.246c-.27.314-.71.868-.77 1.47-.09.924.28 2.042 1.28 3.222 1 1.18 2.52 2.542 4.83 3.25.83.26 1.48.083 2-.27.42-.287.69-.74.78-1.07.08-.33.08-.61.05-.67a.35.35 0 0 0-.19-.22l-1.79-.83a.5.5 0 0 0-.49.04l-.71.54c-.08.06-.23.09-.36.05-.38-.12-1.64-.66-2.34-1.77-.18-.28-.22-.43-.16-.57l.52-.7a.47.47 0 0 0 .06-.47l-.78-1.83a.44.44 0 0 0-.33-.27 1.86 1.86 0 0 0-.37-.03Z" />
                    </svg>
                    WhatsApp
                </a>
            </div>
        </div>
    </div>
@endif
</div>


<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('scroll-to-ticket', () => {
            const el = document.getElementById('ticket-summary');
            if (el) {
                el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
            }
        });
    });
</script>

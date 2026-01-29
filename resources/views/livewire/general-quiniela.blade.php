<div>
    <div class="w-full bg-primarycolor shadow-sm p-2 md:py-4">
        <div class="bg-white rounded-xl border border-primaryhcolor px-2 py-1 grid grid-cols-12 gap-1 md:gap-6 items-center max-w-4xl mx-auto md:px-4 md:py-3">

            <!-- Logo + Precio -->
            <div class="col-span-3 flex flex-col items-center md:items-start gap-2 md:gap-3">
                <img
                    src="/imgs/logos/logoquinielas.png"
                    alt="Logo Quinielas"
                    class="md:w-28 w-20 object-contain"
                />

                <div class="flex md:flex-row flex-col items-center gap-1 md:gap-2">
                    <span class="text-sm font-medium text-gray-500">Precio</span>
                    <span class=" text-primarycolor *:text-lg font-bold">
                        {{ '$ ' . ($quiniela->price ?? 'N/A') }}
                    </span>
                </div>
            </div>

            <!-- Información central -->
            <div class="col-span-9 text-center space-y-1 md:space-y-2">
                <p class="text-xs uppercase tracking-wide text-amber-600">
                    Quinielas del Bajío
                </p>

                <h1 class="inline-block text-lg md:text-2xl font-semibold text-primarycolor">
                    {{ $quiniela->title ?? '' }}
                </h1>

                <div class="text-xs md:text-sm text-left text-gray-700 space-y-1">
                    <p class="text-xs">
                        <span class="font-semibold text-amber-600 text-sm">Partidos:</span>
                        {{ $quiniela->play_start ? \Carbon\Carbon::parse($quiniela->play_start)->locale('es')->translatedFormat('d F Y') : '' }}
                        al
                        {{ $quiniela->play_end ? \Carbon\Carbon::parse($quiniela->play_end)->locale('es')->translatedFormat('d F Y') : '' }}
                    </p>

                    <p class="text-xs">
                        <span class="font-semibold text-amber-600 text-[10px]">Ventas:</span>
                        {{ $quiniela->sales_start ? \Carbon\Carbon::parse($quiniela->sales_start)->locale('es')->translatedFormat('d F Y') : '' }}
                        al
                        {{ $quiniela->sales_end ? \Carbon\Carbon::parse($quiniela->sales_end)->locale('es')->translatedFormat('d F Y H:i') : '' }}
                    </p>
                </div>
            </div>

            <!-- Acción -->
            <div class="col-span-12 flex justify-center md:justify-end gap-2">
                <x-button-primary
                    type="button"
                    class="flex items-center gap-2 !px-2 py-1 ml-auto"
                    wire:click="toggleRulesModal"
                >
                    <svg xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="currentColor"
                        class="size-5 text-white">
                        <path fill-rule="evenodd"
                            d="M4.125 3C3.089 3 2.25 3.84 2.25 4.875V18a3 3 0 0 0 3 3h15a3 3 0 0 1-3-3V4.875C17.25 3.839 16.41 3 15.375 3H4.125Z"
                            clip-rule="evenodd" />
                    </svg>
                    Reglamento
                </x-button-primary>

                <button wire:click="toggleWhatsModal" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white text-sm md:text-base font-semibold shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                        <path d="M12 2C6.486 2 2 6.201 2 11.294c0 1.747.478 3.393 1.385 4.86L2 22l6.05-1.93C9.43 20.54 10.705 20.79 12 20.79 17.514 20.79 22 16.586 22 11.494 22 6.401 17.514 2 12 2Zm0 2c4.087 0 7.4 3.127 7.4 7.494 0 4.368-3.313 7.495-7.4 7.495-1.13 0-2.246-.235-3.26-.692l-.233-.106-3.57 1.139 1.16-3.18-.151-.244C4.365 15.19 4 13.98 4 11.294 4 7.127 7.223 4 12 4Zm-3.09 3.75a.7.7 0 0 0-.52.246c-.27.314-.71.868-.77 1.47-.09.924.28 2.042 1.28 3.222 1 1.18 2.52 2.542 4.83 3.25.83.26 1.48.083 2-.27.42-.287.69-.74.78-1.07.08-.33.08-.61.05-.67a.35.35 0 0 0-.19-.22l-1.79-.83a.5.5 0 0 0-.49.04l-.71.54c-.08.06-.23.09-.36.05-.38-.12-1.64-.66-2.34-1.77-.18-.28-.22-.43-.16-.57l.52-.7a.47.47 0 0 0 .06-.47l-.78-1.83a.44.44 0 0 0-.33-.27 1.86 1.86 0 0 0-.37-.03Z" />
                    </svg>
                </button>
            </div>

            @if($quiniela->status !== 'open')
                @if($quiniela->status === 'closed')
                    <div class="bg-white col-span-12 md:mt-6 p-5 md:p-4 flex flex-col items-center justify-center rounded-lg min-h-[75vh]">
                        <p class="text-2xl font-bold text-yellow-600 uppercase">
                            Ventas cerradas
                        </p>
                        <p class="text-gray-700 text-center my-6">Da clic en el siguiente botón para ir a los resultados.</p>
                        <x-button-primary type="button" class="px-3 py-2 text-sm md:text-base flex items-center w-fit mx-auto" onclick="window.location='{{ route('quiniela.results') }}'">
                                Ver resultados
                        </x-button-primary>
                    </div>
                @else
                    <div class="bg-white col-span-12 md:mt-6 p-5 md:p-4 flex flex-col items-center justify-center rounded-lg min-h-[75vh]">
                        <p class="text-2xl font-bold text-yellow-600 uppercase">
                            Quiniela finalizada
                        </p>
                        <p class="text-gray-700 text-center my-6">Gracias por tu preferencia. Espera la nueva quiniela.</p>
                    </div>
                @endif
            @else
            <div class="bg-white w-full md:mt-6 p-1 md:p-4 rounded-lg col-span-12">

                @php
                    $eventMatches = $quiniela->eventMatches ?? collect();
                    $regularMatches = $eventMatches->where('is_substitute', false);
                    $substituteMatches = $eventMatches->where('is_substitute', true);
                @endphp

                <!-- Tabla responsiva: en móvil se mantiene en línea con scroll horizontal -->
                <div class="-mx-3 md:mx-0 overflow-x-auto">
                    <div class="min-w-[340px] md:min-w-0 px-1 md:px-0">
                        <div class="grid grid-cols-[minmax(0,1fr)_58px_minmax(0,1fr)] md:grid-cols-[minmax(0,1fr)_60px_minmax(0,1fr)] px-1 py-2 rounded-tr rounded-tl bg-amber-50 border border-amber-200 text-xs font-semibold text-amber-700">
                            <div class="text-left">LOCAL</div>
                            <div class="text-center">EMPATE</div>
                            <div class="text-right">VISITANTE</div>
                        </div>

                        <div class="divide-y divide-gray-200 border-x border-gray-200 overflow-hidden">
                    @foreach ($regularMatches as $match)
                        @php
                            $matchGame = $match->matchGame;
                            $homeTeam = $matchGame?->homeTeam;
                            $awayTeam = $matchGame?->awayTeam;
                        @endphp

                        <div class="bg-white px-1 py-2" wire:key="event-match-{{ $match->id }}">
                            <div class="grid grid-cols-[minmax(0,1fr)_58px_minmax(0,1fr)] md:grid-cols-[minmax(0,1fr)_60px_minmax(0,1fr)] items-center gap-1">
                                <!-- Local: nombre+logo + checkbox (permite dobles/triples) -->
                                <label class="flex cursor-pointer min-w-0">
                                    <div class="flex items-center justify-end gap-2 min-w-0 w-3/4">
                                        <span class="font-semibold text-gray-900 text-[10px] md:text-sm truncate">{{ $homeTeam?->name ?? 'Local' }}</span>
                                        @if($homeTeam?->logo)
                                            <img class="size-7 object-contain ml-auto" src="{{ Storage::url($homeTeam->logo) }}" alt="{{ $homeTeam->name }}" />
                                        @endif
                                    </div>
                                    <input
                                        type="checkbox"
                                        class="checksquiniela ml-auto"
                                        wire:model.live="picks.{{ $match->id }}.H"
                                    />
                                </label>

                                <!-- Empate: checkbox -->
                                <div class="flex items-center justify-center w-[58px] md:w-[60px]">
                                    <input
                                        type="checkbox"
                                        class="checksquiniela"
                                        wire:model.live="picks.{{ $match->id }}.D"
                                    />
                                </div>

                                <!-- Visitante: checkbox + logo+nombre -->
                                <label class="flex gap-2 cursor-pointer min-w-0">
                                    <input
                                        type="checkbox"
                                        class="checksquiniela"
                                        wire:model.live="picks.{{ $match->id }}.A"
                                    />
                                    <div class="flex items-center justify-end gap-2 min-w-0 w-3/4">
                                        @if($awayTeam?->logo)
                                            <img class="size-7 object-contain mr-auto" src="{{ Storage::url($awayTeam->logo) }}" alt="{{ $awayTeam->name }}" />
                                        @endif
                                        <span class="font-semibold text-gray-900 text-[10px] md:text-sm truncate">{{ $awayTeam?->name ?? 'Visitante' }}</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    @endforeach
                        </div>
                    </div>
                </div>

                @if($substituteMatches->isNotEmpty())
                    <div class="">
                        <div class="-mx-3 md:mx-0 overflow-x-auto">
                            <div class="min-w-[340px] md:min-w-0 px-1 md:px-0">
                                <div class="flex items-center gap-1 px-2 py-1 bg-amber-50 border-x border-t border-amber-200 text-xs font-semibold text-amber-700">
                                    <div class="w-3/6">PARTIDO SUPLENTE</div>
                                    <p class="text-[8px] md:text-xs text-gray-500 leading-tight w-4/6">El siguiente partido contará unicamente en caso de que alguno de los anteriores sea suspendido, no olvides marcarlo.</p>
                                </div>

                                <div class="divide-y divide-amber-200 border border-amber-200 rounded-br rounded-bl overflow-hidden">
                            @foreach($substituteMatches as $match)
                                @php
                                    $matchGame = $match->matchGame;
                                    $homeTeam = $matchGame?->homeTeam;
                                    $awayTeam = $matchGame?->awayTeam;
                                @endphp

                                <div class="bg-amber-50 p-2" wire:key="event-match-substitute-{{ $match->id }}">
                                    <div class="grid grid-cols-[minmax(0,1fr)_58px_minmax(0,1fr)] md:grid-cols-[minmax(0,1fr)_60px_minmax(0,1fr)] items-center gap-1">
                                <!-- Local: nombre+logo + checkbox (permite dobles/triples) -->
                                <label class="flex cursor-pointer min-w-0">
                                    <div class="flex items-center justify-end gap-2 min-w-0 w-3/4">
                                        <span class="font-semibold text-gray-900 text-[10px] md:text-sm truncate">{{ $homeTeam?->name ?? 'Local' }}</span>
                                        @if($homeTeam?->logo)
                                            <img class="size-7 object-contain ml-auto" src="{{ Storage::url($homeTeam->logo) }}" alt="{{ $homeTeam->name }}" />
                                        @endif
                                    </div>
                                    <input
                                        type="checkbox"
                                        class="checksquiniela ml-auto"
                                        wire:model.live="picks.{{ $match->id }}.H"
                                    />
                                </label>

                                <!-- Empate: checkbox -->
                                <div class="flex items-center justify-center w-[58px] md:w-[60px]">
                                    <input
                                        type="checkbox"
                                        class="checksquiniela"
                                        wire:model.live="picks.{{ $match->id }}.D"
                                    />
                                </div>

                                <!-- Visitante: checkbox + logo+nombre -->
                                <label class="flex gap-2 cursor-pointer min-w-0">
                                    <input
                                        type="checkbox"
                                        class="checksquiniela"
                                        wire:model.live="picks.{{ $match->id }}.A"
                                    />
                                    <div class="flex items-center justify-end gap-2 min-w-0 w-3/4">
                                        @if($awayTeam?->logo)
                                            <img class="size-7 object-contain mr-auto" src="{{ Storage::url($awayTeam->logo) }}" alt="{{ $awayTeam->name }}" />
                                        @endif
                                        <span class="font-semibold text-gray-900 text-[10px] md:text-sm truncate">{{ $awayTeam?->name ?? 'Visitante' }}</span>
                                    </div>
                                </label>
                            </div>
                                </div>
                            @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Resumen de jugadas con dobles/triples -->
                <div class="mt-2 -mx-1 md:mx-0 overflow-x-auto">
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-2 flex flex-col md:flex-row md:items-center md:justify-between gap-2 text-xs md:text-sm">
                        <div class="space-y-1">
                            <p class="text-amber-700">Configuración de jugadas</p>
                            <p class="text-gray-600 text-[10px] md:text-xs">
                                Marca 1 opción por partido para una jugada simple,
                                2 opciones para un doble y 3 opciones para un triple.
                            </p>
                        </div>
                        <div class="flex justify-between md:flex-row md:items-center gap-1 md:gap-4">
                            <div class="flex items-center gap-1">
                                <span class="text-gray-600 text-[10px] md:text-xs">Jugadas (combinaciones):</span>
                                <span class="font-bold text-amber-700 text-base md:text-lg">
                                    {{ $totalCombinations }}
                                </span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="text-gray-600 text-[10px] md:text-xs">Total a pagar:</span>
                                <span class="font-bold text-emerald-700 text-base md:text-lg">
                                    {{ $totalCombinations > 0 && ($quiniela->price ?? 0) > 0 ? '$ ' . number_format($totalPrice, 2) : '$ 0.00' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Datos del jugador y guardado de ticket -->
                <div class="mt-2 w-full flex flex-col md:flex-row md:items-end gap-3">
                    <div class="flex-1">
                        <label class="block text-[11px] md:text-xs font-semibold text-gray-700 mb-1">
                            Nombre del jugador
                        </label>
                        <input
                            type="text"
                            wire:model.live="player_name"
                            class="w-full border border-gray-300 rounded-lg px-2 py-1 text-xs md:text-sm focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500"
                            placeholder="Escribe tu nombre completo"
                        />
                        @error('player_name')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex md:justify-end">
                        <x-button-primary
                            type="button"
                            class="px-3 py-1 text-sm md:text-base flex items-center mx-auto"
                            wire:click="saveTicket"
                        >
                            Guardar quiniela
                        </x-button-primary>
                    </div>
                </div>

                @if($last_ticket_folio)
                    <div id="ticket-summary" class="mt-4 w-full">
                        <div class="bg-emerald-50 border border-emerald-300 rounded-lg px-3 py-2 text-xs md:text-sm flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                            <div>
                                <p class="font-semibold text-emerald-700">{{$player_name ?? ''}} tu quiniela se generó correctamente</p>
                                <p class="text-[10px] md:text-xs text-emerald-700">Conserva este folio como comprobante.</p>
                                <p class="text-[10px] md:text-xs text-emerald-700">Para jugar otra quiniela llena el formulario nuevamente.</p>
                            </div>
                            <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4">
                                <div class="flex items-center gap-1">
                                    <span class="text-emerald-700 text-[10px] md:text-xs">Folio:</span>
                                    <span class="font-mono font-bold text-emerald-800 text-lg md:text-xl">{{ $last_ticket_folio }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="text-emerald-700 text-[10px] md:text-xs">Total a pagar:</span>
                                    <span class="font-bold text-emerald-800 text-lg md:text-xl">$ {{ number_format($last_ticket_total, 2) }}</span>
                                </div>
                            </div>
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
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[80vh] overflow-y-auto p-4 md:p-6 text-sm md:text-base">
            <div class="flex items-start justify-between gap-4 mb-3">
                <h2 class="text-base md:text-lg font-bold text-secondarycolor">Reglamento de la quiniela</h2>
                <button
                    type="button"
                    class="text-gray-400 hover:text-gray-600"
                    wire:click="toggleRulesModal"
                >
                    ✕
                </button>
            </div>

            <div class="space-y-3 text-[11px] md:text-sm text-gray-800 leading-relaxed">
                <p>
                    Si decides participar en nuestra quiniela estás aceptando estas reglas.
                    En caso de no estar de acuerdo con alguna de ellas favor de no participar.
                </p>

                <ul class="list-disc list-inside space-y-2">
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

                <p class="text-[10px] md:text-xs text-gray-500 mt-2">
                    Te recomendamos leer cuidadosamente cada punto antes de participar.
                </p>
            </div>

            <div class="mt-4 flex justify-end">
                <x-button-primary type="button" class="px-3 py-1 text-xs md:text-sm" wire:click="toggleRulesModal">
                    Entendido
                </x-button-primary>
            </div>
        </div>
    </div>
@endif

@if($showWhatsModal)
    <div class="fixed inset-0 z-40 bg-black bg-opacity-50 flex items-center justify-center px-3" wire:click.self="toggleWhatsModal">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[80vh] overflow-y-auto p-4 md:p-6 text-sm md:text-base">
            <div class="flex items-start justify-between gap-4 mb-3">
                <h2 class="text-base md:text-lg font-bold text-emerald-500">Contacto de WhatsApp</h2>
                <button type="button" class="text-gray-400 hover:text-gray-600" wire:click="toggleWhatsModal">
                    ✕
                </button>
            </div>
            <div class="space-y-3 text-sm md:text-base text-gray-800 leading-relaxed flex flex-col">
                <p>Tienes alguna duda o quieres enviar tu pago por WhatsApp, escribe al siguiente número:</p>
                <a
                    href="https://wa.me/{{ $whatsNumber }}?text={{ $whatsText }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white text-sm md:text-base font-semibold shadow mx-auto"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
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

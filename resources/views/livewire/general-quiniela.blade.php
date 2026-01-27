<div>
    <div class="w-full bg-[#E3B02B] shadow-sm rounded-xl p-2">
        <div class="bg-white rounded-xl border border-amber-200 px-2 py-1 grid grid-cols-12 gap-1 md:gap-6 items-center">

            <!-- Logo + Precio -->
            <div class="col-span-3 flex flex-col items-center md:items-start gap-2 md:gap-3">
                <img
                    src="/imgs/logos/logoquinielas.png"
                    alt="Logo Quinielas"
                    class="md:w-28 w-14"
                />

                <div class="flex md:flex-row flex-col items-center gap-1 md:gap-2">
                    <span class="text-xs md:text-sm font-medium text-gray-500">Precio</span>
                    <span class="px-2 md:px-3 py-1 rounded-full bg-primarycolor text-white text-sm md:text-lg font-bold">
                        {{ '$ ' . ($quiniela->price ?? 'N/A') }}
                    </span>
                </div>
            </div>

            <!-- Información central -->
            <div class="col-span-9 text-center space-y-1 md:space-y-2">
                <p class="text-xs uppercase tracking-wide text-amber-600">
                    Quinielas del Bajío
                </p>

                <h1 class="inline-block text-sm md:text-2xl font-bold text-white bg-primarycolor px-2 md:px-4 py-1 rounded-lg">
                    {{ $quiniela->title ?? '' }}
                </h1>

                <div class="text-xs md:text-sm text-left text-gray-700 space-y-1">
                    <p class="text-[10px]">
                        <span class="font-semibold text-amber-600 text-xs">Partidos:</span>
                        {{ $quiniela->play_start ? \Carbon\Carbon::parse($quiniela->play_start)->locale('es')->translatedFormat('d F Y') : '' }}
                        al
                        {{ $quiniela->play_end ? \Carbon\Carbon::parse($quiniela->play_end)->locale('es')->translatedFormat('d F Y') : '' }}
                    </p>

                    <p class="text-[10px]">
                        <span class="font-semibold text-amber-600 text-xs">Ventas:</span>
                        {{ $quiniela->sales_start ? \Carbon\Carbon::parse($quiniela->sales_start)->locale('es')->translatedFormat('d F Y') : '' }}
                        al
                        {{ $quiniela->sales_end ? \Carbon\Carbon::parse($quiniela->sales_end)->locale('es')->translatedFormat('d F Y H:i') . ' HRS' : '' }}
                    </p>
                </div>
            </div>

            <!-- Acción -->
            <div class="col-span-12 flex justify-center md:justify-end">
                <x-button-primary class="flex items-center gap-2 !px-2 py-1 ml-auto">
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
            </div>

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
                            $groupName = 'pick_' . $match->id;
                        @endphp

                        <div class="bg-white px-1 py-2" wire:key="event-match-{{ $match->id }}">
                            <div class="grid grid-cols-[minmax(0,1fr)_58px_minmax(0,1fr)] md:grid-cols-[minmax(0,1fr)_60px_minmax(0,1fr)] items-center gap-1">
                                <!-- Local: nombre+logo + radio -->
                                <label class="flex cursor-pointer min-w-0">
                                    <div class="flex items-center justify-end gap-2 min-w-0 w-3/4">
                                        <span class="font-semibold text-gray-900 text-[10px] md:text-sm truncate">{{ $homeTeam?->name ?? 'Local' }}</span>
                                        @if($homeTeam?->logo)
                                            <img class="size-6 object-contain ml-auto" src="{{ Storage::url($homeTeam->logo) }}" alt="{{ $homeTeam->name }}" />
                                        @endif
                                    </div>
                                    <input
                                        type="radio"
                                        class="checksquiniela ml-auto"
                                        name="{{ $groupName }}"
                                        value="H"
                                        wire:model.live="picks.{{ $match->id }}"
                                    />
                                </label>

                                <!-- Empate: radio -->
                                <div class="flex items-center justify-center w-[58px] md:w-[60px]">
                                    <input
                                        type="radio"
                                        class="checksquiniela"
                                        name="{{ $groupName }}"
                                        value="D"
                                        wire:model.live="picks.{{ $match->id }}"
                                    />
                                </div>

                                <!-- Visitante: radio + logo+nombre -->
                                <label class="flex gap-2 cursor-pointer min-w-0">
                                    <input
                                        type="radio"
                                        class="checksquiniela"
                                        name="{{ $groupName }}"
                                        value="A"
                                        wire:model.live="picks.{{ $match->id }}"
                                    />
                                    <div class="flex items-center justify-end gap-2 min-w-0 w-3/4">
                                        @if($awayTeam?->logo)
                                            <img class="size-6 object-contain mr-auto" src="{{ Storage::url($awayTeam->logo) }}" alt="{{ $awayTeam->name }}" />
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
                                    $groupName = 'pick_' . $match->id;
                                @endphp

                                <div class="bg-amber-50 p-2" wire:key="event-match-substitute-{{ $match->id }}">
                                    <div class="grid grid-cols-[minmax(0,1fr)_58px_minmax(0,1fr)] md:grid-cols-[minmax(0,1fr)_60px_minmax(0,1fr)] items-center gap-1">
                                <!-- Local: nombre+logo + radio -->
                                <label class="flex cursor-pointer min-w-0">
                                    <div class="flex items-center justify-end gap-2 min-w-0 w-3/4">
                                        <span class="font-semibold text-gray-900 text-[10px] md:text-sm truncate">{{ $homeTeam?->name ?? 'Local' }}</span>
                                        @if($homeTeam?->logo)
                                            <img class="size-6 object-contain ml-auto" src="{{ Storage::url($homeTeam->logo) }}" alt="{{ $homeTeam->name }}" />
                                        @endif
                                    </div>
                                    <input
                                        type="radio"
                                        class="checksquiniela ml-auto"
                                        name="{{ $groupName }}"
                                        value="H"
                                        wire:model.live="picks.{{ $match->id }}"
                                    />
                                </label>

                                <!-- Empate: radio -->
                                <div class="flex items-center justify-center w-[58px] md:w-[60px]">
                                    <input
                                        type="radio"
                                        class="checksquiniela"
                                        name="{{ $groupName }}"
                                        value="D"
                                        wire:model.live="picks.{{ $match->id }}"
                                    />
                                </div>

                                <!-- Visitante: radio + logo+nombre -->
                                <label class="flex gap-2 cursor-pointer min-w-0">
                                    <input
                                        type="radio"
                                        class="checksquiniela"
                                        name="{{ $groupName }}"
                                        value="A"
                                        wire:model.live="picks.{{ $match->id }}"
                                    />
                                    <div class="flex items-center justify-end gap-2 min-w-0 w-3/4">
                                        @if($awayTeam?->logo)
                                            <img class="size-6 object-contain mr-auto" src="{{ Storage::url($awayTeam->logo) }}" alt="{{ $awayTeam->name }}" />
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
            </div>

        </div>
        
    </div>
</div>

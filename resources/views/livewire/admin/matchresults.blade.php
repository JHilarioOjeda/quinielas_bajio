<div>
    <x-loading functionsList="saveMatch, saveAll" />

    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-white leading-tight">
            Captura de resultados
        </h2>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-gray-200">
                <div class="p-4 sm:p-6 lg:p-8 bg-white">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-end mb-6">
                        <div class="w-full sm:w-1/2">
                            <p class="text-sm font-semibold uppercase tracking-wide text-secondarycolor">Quiniela</p>
                            <p class="text-xl font-semibold text-primarycolor">{{ $quiniela->title ?? '' }}</p>
                            <div>
                                <span class="text-red-500 text-sm italic">
                                    @error('selectedEventId') {{ $message }} @enderror
                                </span>
                            </div>
                        </div>

                        <x-button-primary class="w-full sm:w-auto sm:ml-auto justify-center" wire:click="saveAll">
                            Guardar todos
                        </x-button-primary>
                    </div>

                    @if(($eventMatches ?? collect())->isEmpty())
                        <div class="rounded-lg border border-dashed border-gray-300 py-10 text-center text-base text-gray-500">
                            No hay partidos cargados para esta quiniela.
                        </div>
                    @else
                        <!-- Tarjetas (móvil) -->
                        <div class="space-y-4 lg:hidden">
                            @foreach($eventMatches as $eventMatch)
                                @php($match = $eventMatch->matchGame)
                                <div class="rounded-xl border border-gray-200 shadow-sm p-4">
                                    <div class="flex items-center justify-between gap-2 flex-wrap mb-3">
                                        <div class="flex items-center gap-2 min-w-0">
                                            @if($match?->homeTeam?->logo)
                                                <img src="{{ Storage::url($match->homeTeam->logo) }}" class="h-8 w-8 object-contain" alt="{{ $match->homeTeam->name }}" />
                                            @endif
                                            <span class="font-semibold text-base truncate">{{ $match?->homeTeam?->name ?? 'Local' }}</span>
                                            <span class="text-gray-400 text-sm">vs</span>
                                            <span class="font-semibold text-base truncate">{{ $match?->awayTeam?->name ?? 'Visita' }}</span>
                                            @if($match?->awayTeam?->logo)
                                                <img src="{{ Storage::url($match->awayTeam->logo) }}" class="h-8 w-8 object-contain" alt="{{ $match->awayTeam->name }}" />
                                            @endif
                                        </div>
                                        @if($eventMatch->is_substitute)
                                            <span class="text-xs font-semibold px-2 py-1 rounded bg-yellow-100 text-yellow-800 whitespace-nowrap">Suplente</span>
                                        @endif
                                    </div>

                                    <p class="text-sm text-gray-500 mb-3">
                                        {{ $match?->match_date ? \Carbon\Carbon::parse($match->match_date)->format('d/m/Y H:i') : 'Sin fecha' }}
                                    </p>

                                    <div class="mb-3">
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Estatus</label>
                                        <select wire:model.live="results.{{ $match->id }}.status" class="inputcatalogues w-full">
                                            <option value="pending">Pendiente</option>
                                            <option value="finished">Finalizado</option>
                                        </select>
                                        <span class="text-red-500 text-sm italic">
                                            @error("results.$match->id.status") {{ $message }} @enderror
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3 mb-4">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-1">Marcador local</label>
                                            <input
                                                type="number"
                                                min="0"
                                                class="inputcatalogues w-full text-center text-lg"
                                                wire:model.live="results.{{ $match->id }}.home_score"
                                                @disabled(($results[$match->id]['status'] ?? 'pending') === 'pending')
                                            />
                                            <span class="text-red-500 text-sm italic">
                                                @error("results.$match->id.home_score") {{ $message }} @enderror
                                            </span>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-1">Marcador visita</label>
                                            <input
                                                type="number"
                                                min="0"
                                                class="inputcatalogues w-full text-center text-lg"
                                                wire:model.live="results.{{ $match->id }}.away_score"
                                                @disabled(($results[$match->id]['status'] ?? 'pending') === 'pending')
                                            />
                                            <span class="text-red-500 text-sm italic">
                                                @error("results.$match->id.away_score") {{ $message }} @enderror
                                            </span>
                                        </div>
                                    </div>

                                    <x-button-primary class="w-full justify-center" wire:click="saveMatch({{ (int) $match->id }})">
                                        Guardar partido
                                    </x-button-primary>
                                </div>
                            @endforeach
                        </div>

                        <!-- Tabla (escritorio) -->
                        <div class="hidden lg:block mt-2 rounded-lg border border-gray-200 max-h-[75vh] w-full overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-base">
                                <thead class="bg-primarycolor text-white">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-semibold">Partido</th>
                                        <th class="px-4 py-3 text-left font-semibold">Fecha</th>
                                        <th class="px-4 py-3 text-center font-semibold">Estatus</th>
                                        <th class="px-4 py-3 text-center font-semibold">Local</th>
                                        <th class="px-4 py-3 text-center font-semibold">Visita</th>
                                        <th class="px-4 py-3"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($eventMatches as $eventMatch)
                                        @php($match = $eventMatch->matchGame)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center gap-3 flex-wrap">
                                                    <div class="flex items-center gap-2 min-w-0">
                                                        @if($match?->homeTeam?->logo)
                                                            <img src="{{ Storage::url($match->homeTeam->logo) }}" class="h-8 w-8 object-contain" alt="{{ $match->homeTeam->name }}" />
                                                        @endif
                                                        <span class="font-semibold truncate">{{$match?->homeTeam?->name ?? 'Local' }}</span>
                                                    </div>

                                                    <span class="text-gray-500 whitespace-nowrap">vs</span>

                                                    <div class="flex items-center gap-2 min-w-0">
                                                        <span class="font-semibold truncate">{{$match?->awayTeam?->name ?? 'Visita' }}</span>
                                                        @if($match?->awayTeam?->logo)
                                                            <img src="{{ Storage::url($match->awayTeam->logo) }}" class="h-8 w-8 object-contain" alt="{{ $match->awayTeam->name }}" />
                                                        @endif
                                                    </div>

                                                    @if($eventMatch->is_substitute)
                                                        <span class="ml-2 text-sm font-semibold px-2 py-1 rounded bg-yellow-100 text-yellow-800 whitespace-nowrap">Suplente</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                                                {{ $match?->match_date ? \Carbon\Carbon::parse($match->match_date)->format('d/m/Y H:i') : '-' }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <select wire:model.live="results.{{ $match->id }}.status" class="inputcatalogues !w-36">
                                                    <option value="pending">Pendiente</option>
                                                    <option value="finished">Finalizado</option>
                                                </select>
                                                <div>
                                                    <span class="text-red-500 text-sm italic">
                                                        @error("results.$match->id.status") {{ $message }} @enderror
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <input
                                                    type="number"
                                                    min="0"
                                                    class="inputcatalogues !w-20 text-center"
                                                    wire:model.live="results.{{ $match->id }}.home_score"
                                                    @disabled(($results[$match->id]['status'] ?? 'pending') === 'pending')
                                                />
                                                <div>
                                                    <span class="text-red-500 text-sm italic">
                                                        @error("results.$match->id.home_score") {{ $message }} @enderror
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <input
                                                    type="number"
                                                    min="0"
                                                    class="inputcatalogues !w-20 text-center"
                                                    wire:model.live="results.{{ $match->id }}.away_score"
                                                    @disabled(($results[$match->id]['status'] ?? 'pending') === 'pending')
                                                />
                                                <div>
                                                    <span class="text-red-500 text-sm italic">
                                                        @error("results.$match->id.away_score") {{ $message }} @enderror
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                                <x-button-primary class="justify-center" wire:click="saveMatch({{ (int) $match->id }})">
                                                    Guardar
                                                </x-button-primary>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

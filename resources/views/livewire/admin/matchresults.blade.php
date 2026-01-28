<div>
    <x-loading functionsList="saveMatch, saveAll" />

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Captura de resultados
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-end">
                        <div class="w-full sm:w-1/2">
                            <p class="text-secondarycolor">Quiniela:</p>
                            <p class="text-xl font-semibold text-primarycolor">{{ $quiniela->title ?? '' }}</p>
                            <div>
                                <span class="text-red-500 text-xs italic">
                                    @error('selectedEventId') {{ $message }} @enderror
                                </span>
                            </div>
                        </div>

                        <x-button-primary class="w-full sm:w-auto sm:ml-auto justify-center" wire:click="saveAll">
                            Guardar todos
                        </x-button-primary>
                    </div>

                    <div class="mt-6 rounded max-h-[75vh] w-full overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-primarycolor text-white">
                                <tr>
                                    <th class="px-3 py-2 text-left">Partido</th>
                                    <th class="px-3 py-2 text-left">Fecha</th>
                                    <th class="px-3 py-2 text-center">Estatus</th>
                                    <th class="px-3 py-2 text-center">Local</th>
                                    <th class="px-3 py-2 text-center">Visita</th>
                                    <th class="px-3 py-2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(($eventMatches ?? collect()) as $eventMatch)
                                    @php($match = $eventMatch->matchGame)
                                    <tr class="border-b">
                                        <td class="px-3 py-2">
                                            <div class="flex items-center gap-3 flex-wrap sm:flex-nowrap">
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
                                                    <span class="ml-2 text-xs px-2 py-1 rounded bg-yellow-100 text-yellow-800 whitespace-nowrap">Suplente</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-3 py-2 text-sm text-gray-700 whitespace-nowrap">
                                            {{ $match?->match_date ? \Carbon\Carbon::parse($match->match_date)->format('d/m/Y H:i') : '-' }}
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            <select wire:model.live="results.{{ $match->id }}.status" class="inputcatalogues !w-36">
                                                <option value="pending">Pendiente</option>
                                                <option value="finished">Finalizado</option>
                                            </select>
                                            <div>
                                                <span class="text-red-500 text-xs italic">
                                                    @error("results.$match->id.status") {{ $message }} @enderror
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            <input
                                                type="number"
                                                min="0"
                                                class="inputcatalogues !w-20 text-center"
                                                wire:model.live="results.{{ $match->id }}.home_score"
                                                @disabled(($results[$match->id]['status'] ?? 'pending') === 'pending')
                                            />
                                            <div>
                                                <span class="text-red-500 text-xs italic">
                                                    @error("results.$match->id.home_score") {{ $message }} @enderror
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            <input
                                                type="number"
                                                min="0"
                                                class="inputcatalogues !w-20 text-center"
                                                wire:model.live="results.{{ $match->id }}.away_score"
                                                @disabled(($results[$match->id]['status'] ?? 'pending') === 'pending')
                                            />
                                            <div>
                                                <span class="text-red-500 text-xs italic">
                                                    @error("results.$match->id.away_score") {{ $message }} @enderror
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-3 py-2 text-center whitespace-nowrap">
                                            <x-button-primary class="w-full sm:w-auto justify-center" wire:click="saveMatch({{ (int) $match->id }})">
                                                Guardar
                                            </x-button-primary>
                                        </td>
                                    </tr>
                                @endforeach

                                @if(($eventMatches ?? collect())->isEmpty())
                                    <tr>
                                        <td colspan="6" class="border px-3 py-6 text-center text-gray-600">
                                            No hay partidos cargados para esta quiniela.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

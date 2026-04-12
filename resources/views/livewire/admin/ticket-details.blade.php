<div>
    <div class="text-xs font-semibold text-gray-600 mb-1">
        Jugadas de la quiniela (edición de administrador)
    </div>

    @if($ticket->ticketMatches->isEmpty())
        <div class="text-xs text-gray-500">
            Este ticket no tiene jugadas registradas.
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs border border-gray-200">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-2 py-1 text-left border border-gray-200">Partido</th>
                        <th class="px-2 py-1 text-center border border-gray-200">Local</th>
                        <th class="px-2 py-1 text-center border border-gray-200">Empate</th>
                        <th class="px-2 py-1 text-center border border-gray-200">Visita</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ticket->ticketMatches as $ticketMatch)
                        @php
                            $match = $ticketMatch->matchGame;
                            $homeTeam = $match?->homeTeam?->name;
                            $awayTeam = $match?->awayTeam?->name;
                        @endphp
                        <tr>
                            <td class="px-2 py-1 border border-gray-200 align-top">
                                @if($match)
                                    <div class="flex items-center gap-2 text-base">
                                        <span class="font-medium">{{ $homeTeam }}</span>
                                        <span class="text-gray-500">vs</span>
                                        <span class="font-medium">{{ $awayTeam }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-500">Partido no disponible</span>
                                @endif
                            </td>
                            <td class="px-2 py-1 border border-gray-200 text-center align-middle">
                                <input
                                    type="checkbox"
                                    class="checksquiniela"
                                    wire:model.live="picks.{{ $ticketMatch->id }}.H"
                                />
                            </td>
                            <td class="px-2 py-1 border border-gray-200 text-center align-middle">
                                <input
                                    type="checkbox"
                                    class="checksquiniela"
                                    wire:model.live="picks.{{ $ticketMatch->id }}.D"
                                />
                            </td>
                            <td class="px-2 py-1 border border-gray-200 text-center align-middle">
                                <input
                                    type="checkbox"
                                    class="checksquiniela"
                                    wire:model.live="picks.{{ $ticketMatch->id }}.A"
                                />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-2 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-[11px] sm:text-xs">
            <div class="bg-amber-50 border border-amber-200 rounded px-2 py-1 flex-1">
                <p class="text-amber-700 font-semibold">Configuración de jugadas del ticket</p>
                <p class="text-gray-600">Marca 1 opción por partido para simple, 2 para doble y 3 para triple.</p>
                <div class="mt-1 flex flex-wrap items-center gap-3">
                    <span class="text-gray-600">Jugadas (combinaciones):
                        <span class="font-bold text-amber-700">{{ $totalCombinations }}</span>
                    </span>
                    <span class="text-gray-600">Total a pagar:
                        <span class="font-bold text-emerald-700">
                            {{ $totalCombinations > 0 && ($ticket->quinielaEvent->price ?? 0) > 0 ? '$ ' . number_format($totalPrice, 2) : '$ 0.00' }}
                        </span>
                    </span>
                </div>
            </div>

            <div class="flex sm:justify-end">
                <x-button-primary
                    type="button"
                    class="px-3 py-1 text-xs sm:text-sm flex items-center mx-auto sm:mx-0"
                    wire:click="savePredictions"
                >
                    Guardar cambios
                </x-button-primary>
            </div>
        </div>
    @endif
</div>

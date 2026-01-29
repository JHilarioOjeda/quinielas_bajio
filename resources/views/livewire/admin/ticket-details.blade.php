<div>
    <div class="text-xs font-semibold text-gray-600 mb-1">
        Jugadas de la quiniela
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
                        <th class="px-2 py-1 text-left border border-gray-200">Jugada</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ticket->ticketMatches as $ticketMatch)
                        @php
                            $match = $ticketMatch->matchGame;
                            $homeTeam = $match?->homeTeam?->name;
                            $awayTeam = $match?->awayTeam?->name;
                            $codes = $ticketMatch->predictions->pluck('selection')->unique()->values();
                            $labels = [];
                            foreach ($codes as $code) {
                                $labels[] = match ($code) {
                                    '1' => 'Local',
                                    'X' => 'Empate',
                                    '2' => 'Visita',
                                    default => $code,
                                };
                            }
                            $jugadaTexto = implode(' / ', $labels);
                        @endphp
                        <tr>
                            <td class="px-2 py-1 border border-gray-200 align-top">
                                @if($match)
                                    <span class="font-medium">{{ $homeTeam }}</span>
                                    <span class="text-gray-500">vs</span>
                                    <span class="font-medium">{{ $awayTeam }}</span>
                                @else
                                    <span class="text-gray-500">Partido no disponible</span>
                                @endif
                            </td>
                            <td class="px-2 py-1 border border-gray-200 align-top">
                                @if($jugadaTexto)
                                    <span class="inline-flex flex-wrap gap-1 items-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-primarycolor/10 text-primarycolor font-semibold text-[11px]">
                                            {{ $jugadaTexto }}
                                        </span>
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

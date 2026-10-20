<div>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-white leading-tight">
            Administración de quinielas de jugadores
        </h2>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-gray-200">
                <div class="p-4 sm:p-6 lg:p-8 bg-white">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center mb-5">
                        <x-search-input wireModel="search" placeholder="Buscar por folio o jugador..." class="w-full sm:w-1/2" />
                    </div>

                    @if($tickets->isEmpty())
                        <div class="rounded-lg border border-dashed border-gray-300 py-10 text-center text-base text-gray-500">
                            No hay tickets registrados para los filtros seleccionados.
                        </div>
                    @else
                        <!-- Tarjetas (móvil) -->
                        <div class="space-y-3 md:hidden">
                            @foreach($tickets as $ticket)
                                <div class="rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                                    <div class="p-4">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="min-w-0">
                                                <p class="font-mono text-sm text-gray-500">{{ $ticket->folio }}</p>
                                                <p class="font-semibold text-lg text-gray-900 truncate">{{ $ticket->player_name }}</p>
                                                <p class="text-base text-gray-600">{{ $ticket->phone_number ?? 'N/A' }}</p>
                                            </div>
                                            @if($ticket->isPaid())
                                                <span class="shrink-0 inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                                    Pagado
                                                </span>
                                            @else
                                                <span class="shrink-0 inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                                                    Pendiente
                                                </span>
                                            @endif
                                        </div>

                                        <div class="mt-3 flex items-center justify-between text-base">
                                            <span class="font-semibold text-gray-900">${{ number_format($ticket->amount_paid, 2) }}</span>
                                            <span class="text-gray-500">{{ optional($ticket->created_at)->format('d/m/Y H:i') }}</span>
                                        </div>

                                        <div class="mt-4 flex flex-col gap-2">
                                            <button
                                                wire:click="togglePaymentStatus({{ $ticket->id }})"
                                                class="inline-flex items-center justify-center min-h-11 px-4 py-2.5 rounded-lg text-sm font-semibold text-white {{ $ticket->isPaid() ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }}">
                                                @if($ticket->isPaid())
                                                    Marcar como pendiente
                                                @else
                                                    Marcar como pagado
                                                @endif
                                            </button>

                                            <button
                                                wire:click="toggleDetails({{ $ticket->id }})"
                                                class="inline-flex items-center justify-center min-h-11 px-4 py-2.5 rounded-lg text-sm font-semibold border-2 border-primarycolor text-primarycolor hover:bg-primarycolor hover:text-white">
                                                @if(in_array($ticket->id, $expandedTickets ?? []))
                                                    Ocultar jugadas
                                                @else
                                                    Ver jugadas
                                                @endif
                                            </button>
                                        </div>
                                    </div>

                                    @if(in_array($ticket->id, $expandedTickets ?? []))
                                        <div class="border-t border-gray-200 bg-gray-50 p-4">
                                            <livewire:admin.ticket-details :ticket="$ticket" :key="'ticket-details-m-'.$ticket->id" />
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- Tabla (escritorio) -->
                        <div class="hidden md:block mt-2 rounded-lg border border-gray-200 max-h-[75vh] w-full overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-base">
                                <thead class="bg-primarycolor text-white">
                                    <tr>
                                        <th class="px-4 py-3 text-center font-semibold">Folio</th>
                                        <th class="px-4 py-3 text-left font-semibold">Jugador</th>
                                        <th class="px-4 py-3 text-left font-semibold">Monto</th>
                                        <th class="px-4 py-3 text-center font-semibold">Estatus de pago</th>
                                        <th class="px-4 py-3 text-left font-semibold">Fecha</th>
                                        <th class="px-4 py-3 text-center font-semibold">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($tickets as $ticket)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-center font-mono text-sm text-gray-600">{{ $ticket->folio }}</td>
                                            <td class="px-4 py-3">
                                                <p class="font-semibold text-gray-900">{{ $ticket->player_name }}</p>
                                                <p class="text-sm text-gray-500">{{ $ticket->phone_number ?? 'N/A' }}</p>
                                            </td>
                                            <td class="px-4 py-3 font-semibold text-gray-900">${{ number_format($ticket->amount_paid, 2) }}</td>
                                            <td class="px-4 py-3 text-center">
                                                @if($ticket->isPaid())
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                                        Pagado
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                                                        Pendiente
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ optional($ticket->created_at)->format('d/m/Y H:i') }}</td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="flex flex-col gap-2 items-stretch justify-center xl:flex-row">
                                                    <button
                                                        wire:click="togglePaymentStatus({{ $ticket->id }})"
                                                        class="inline-flex items-center justify-center min-h-10 px-3 py-2 rounded-lg text-sm font-semibold text-white {{ $ticket->isPaid() ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }}">
                                                        @if($ticket->isPaid())
                                                            Marcar pendiente
                                                        @else
                                                            Marcar pagado
                                                        @endif
                                                    </button>

                                                    <button
                                                        wire:click="toggleDetails({{ $ticket->id }})"
                                                        class="inline-flex items-center justify-center min-h-10 px-3 py-2 rounded-lg text-sm font-semibold border-2 border-primarycolor text-primarycolor hover:bg-primarycolor hover:text-white">
                                                        @if(in_array($ticket->id, $expandedTickets ?? []))
                                                            Ocultar
                                                        @else
                                                            Ver jugadas
                                                        @endif
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @if(in_array($ticket->id, $expandedTickets ?? []))
                                            <tr class="bg-gray-50/70">
                                                <td colspan="6" class="px-4 py-3">
                                                    <livewire:admin.ticket-details :ticket="$ticket" :key="'ticket-details-'.$ticket->id" />
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <div class="mt-5">
                        {{ $tickets->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

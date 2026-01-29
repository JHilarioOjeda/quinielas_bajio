<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Administración de quinielas de jugadores
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center mb-4">
                        <x-search-input wireModel="search" placeholder="Buscar por folio o jugador..." class="w-full sm:w-1/2" />
                    </div>

                    <div class="mt-4 rounded max-h-[80vh] w-full overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-primarycolor text-white">
                                <tr>
                                    <th class="px-3 py-2 text-left">Folio</th>
                                    <th class="px-3 py-2 text-left">Jugador</th>
                                    <th class="px-3 py-2 text-left">Monto</th>
                                    <th class="px-3 py-2 text-left">Estatus de pago</th>
                                    <th class="px-3 py-2 text-left">Fecha</th>
                                    <th class="px-3 py-2 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets as $ticket)
                                    <tr class="hover:bg-gray-50">
                                        <td class="border px-3 py-2">{{ $ticket->folio }}</td>
                                        <td class="border px-3 py-2">{{ $ticket->player_name }}</td>
                                        <td class="border px-3 py-2">${{ number_format($ticket->amount_paid, 2) }}</td>
                                        <td class="border px-3 py-2">
                                            @if($ticket->payment_status === 'pagado')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                    Pagado
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                                    Pendiente
                                                </span>
                                            @endif
                                        </td>
                                        <td class="border px-3 py-2">{{ optional($ticket->created_at)->format('d/m/Y H:i') }}</td>
                                        <td class="border px-3 py-2 text-center">
                                            <div class="flex flex-col gap-1 items-center justify-center sm:flex-row sm:gap-2">
                                                <button
                                                    wire:click="togglePaymentStatus({{ $ticket->id }})"
                                                    class="inline-flex items-center px-3 py-1 rounded-md text-sm font-semibold text-white {{ $ticket->payment_status === 'pagado' ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }}">
                                                    @if($ticket->payment_status === 'pagado')
                                                        Marcar como pendiente
                                                    @else
                                                        Marcar como pagado
                                                    @endif
                                                </button>

                                                <button
                                                    wire:click="toggleDetails({{ $ticket->id }})"
                                                    class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold border border-primarycolor text-primarycolor hover:bg-primarycolor hover:text-white">
                                                    @if(in_array($ticket->id, $expandedTickets ?? []))
                                                        Ocultar jugadas
                                                    @else
                                                        Ver jugadas
                                                    @endif
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @if(in_array($ticket->id, $expandedTickets ?? []))
                                        <tr class="bg-gray-50/70">
                                            <td colspan="6" class="border px-3 py-2">
                                                <livewire:admin.ticket-details :ticket="$ticket" :key="'ticket-details-'.$ticket->id" />
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="6" class="border px-3 py-4 text-center text-gray-500">
                                            No hay tickets registrados para los filtros seleccionados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $tickets->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

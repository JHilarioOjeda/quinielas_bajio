<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-white leading-tight">
            Historial de jornadas
        </h2>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-gray-200">
                <div class="p-4 sm:p-6 lg:p-8 bg-white">
                    <p class="text-base text-gray-500 mb-5">
                        Jornadas ya finalizadas, con sus resultados y boletos conservados tal como quedaron al cerrarlas.
                    </p>

                    @if($jornadas->isEmpty())
                        <div class="rounded-lg border border-dashed border-gray-300 py-10 text-center text-base text-gray-500">
                            Todavía no hay jornadas finalizadas.
                        </div>
                    @else
                        <!-- Tarjetas (móvil) -->
                        <div class="space-y-3 md:hidden">
                            @foreach($jornadas as $jornada)
                                <div class="rounded-xl border border-gray-200 shadow-sm p-4">
                                    <p class="font-semibold text-lg text-gray-900">{{ $jornada->title }}</p>
                                    <p class="text-base text-gray-500 mb-3">
                                        @if($jornada->play_start)
                                            {{ \Carbon\Carbon::parse($jornada->play_start)->locale('es')->translatedFormat('d M Y') }}
                                            –
                                            {{ $jornada->play_end ? \Carbon\Carbon::parse($jornada->play_end)->locale('es')->translatedFormat('d M Y') : '' }}
                                        @else
                                            Sin fecha
                                        @endif
                                    </p>
                                    <div class="flex items-center justify-between text-base mb-4">
                                        <span class="text-gray-600">{{ $jornada->tickets_pagados }} boletos pagados</span>
                                        <span class="font-semibold text-gray-900">$ {{ number_format($jornada->recaudo ?? 0, 0, '.', ',') }}</span>
                                    </div>
                                    <a href="{{ route('admin.historial.show', $jornada) }}"
                                       class="inline-flex items-center justify-center w-full min-h-11 px-4 py-2.5 rounded-lg text-sm font-semibold text-white bg-primarycolor hover:bg-primaryhovercolor">
                                        Ver resultados
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <!-- Tabla (escritorio) -->
                        <div class="hidden md:block mt-2 rounded-lg border border-gray-200 max-h-[80vh] w-full overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-base">
                                <thead class="bg-primarycolor text-white">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-semibold">Jornada</th>
                                        <th class="px-4 py-3 text-left font-semibold">Fechas de juego</th>
                                        <th class="px-4 py-3 text-center font-semibold">Boletos pagados</th>
                                        <th class="px-4 py-3 text-center font-semibold">Bolsa</th>
                                        <th class="px-4 py-3 text-center font-semibold">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($jornadas as $jornada)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 font-medium text-gray-900">{{ $jornada->title }}</td>
                                            <td class="px-4 py-3 text-gray-600">
                                                @if($jornada->play_start)
                                                    {{ \Carbon\Carbon::parse($jornada->play_start)->locale('es')->translatedFormat('d M Y') }}
                                                    –
                                                    {{ $jornada->play_end ? \Carbon\Carbon::parse($jornada->play_end)->locale('es')->translatedFormat('d M Y') : '' }}
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-center text-gray-700">{{ $jornada->tickets_pagados }}</td>
                                            <td class="px-4 py-3 text-center font-semibold text-gray-900">
                                                $ {{ number_format($jornada->recaudo ?? 0, 0, '.', ',') }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <a href="{{ route('admin.historial.show', $jornada) }}"
                                                   class="inline-flex items-center justify-center min-h-10 px-4 py-2 rounded-lg text-sm font-semibold text-white bg-primarycolor hover:bg-primaryhovercolor">
                                                    Ver resultados
                                                </a>
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
</x-app-layout>

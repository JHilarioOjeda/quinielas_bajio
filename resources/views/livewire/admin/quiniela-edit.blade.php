<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edición de Quiniela
        </h2>
    </x-slot>

    <div class="py-12 space-y-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 class="font-semibold text-xl text-primarycolor leading-tight mb-5">Información de la Quiniela</h3>

            <div class="w-full flex gap-6 md:flex-row mb-3">
                <div class="w-1/3">
                    <div class="flex flex-col">
                        <p class="text-gray-800">Titulo:</p>
                        <x-input wire:model="title" type="text" class="  !w-full"/>
                        <div>
                            <span class="text-red-500 text-xs italic">
                                @error('title')
                                    {{$message}}
                                @enderror
                            </span>
                        </div>
                    </div>
                </div>
                <div class="w-1/3">
                    <div class="flex flex-col">
                        <p class="text-gray-800">Precio:</p>
                        <x-input wire:model="price" type="number" class="  !w-full"/>
                        <div>
                            <span class="text-red-500 text-xs italic">
                                @error('price')
                                    {{$message}}
                                @enderror
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full flex-col mb-5">
                <p class="text-primarycolor">Fechas de partidos</p>
                <div class="gap-6 flex w-full">
                    <div class="w-1/3">
                        <div class="flex flex-col">
                            <p class="text-gray-800">Fecha de inicio:</p>
                            <x-input wire:model="play_start" type="date" class="  !w-full"/>
                            <div>
                                <span class="text-red-500 text-xs italic">
                                    @error('play_start')
                                        {{$message}}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="w-1/3">
                        <div class="flex flex-col">
                            <p class="text-gray-800">Fecha de fin:</p>
                            <x-input wire:model="play_end" type="date" class="  !w-full"/>
                            <div>
                                <span class="text-red-500 text-xs italic">
                                    @error('play_end')
                                        {{$message}}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        
            <div class="w-full flex-col mb-5">
                <p class="text-primarycolor">Fecha de ventas</p>
                <div class="gap-6 flex w-full">
                    <div class="w-1/3">
                        <div class="flex flex-col">
                            <p class="text-gray-800">Fecha de inicio:</p>
                            <x-input wire:model="sales_start" type="date" class="  !w-full"/>
                            <div>
                                <span class="text-red-500 text-xs italic">
                                    @error('sales_start')
                                        {{$message}}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="w-1/3">
                        <div class="flex flex-col">
                            <p class="text-gray-800">Fecha de fin:</p>
                            <x-input wire:model="sales_end" type="date" class="  !w-full"/>
                            <div>
                                <span class="text-red-500 text-xs italic">
                                    @error('sales_end')
                                        {{$message}}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>

            <div class="w-full flex">
                <div class="w-1/3">
                    <div class="flex flex-col">
                        <p class="text-gray-800">Estatus de quiniela:</p>
                        <p class="text-xs italic text-primarycolor">Cambia el estatus de la quiniela para controlar su disponibilidad.</p>
                        <!-- <x-input wire:model="status" type="text" class="  !w-full"/> -->
                        <select name="status" id="status" wire:model="status" class="inputcatalogues !w-full">
                            <option value="open">Abierta</option>
                            <option value="finished">Finalizada</option>
                        </select>
                        <div>
                            <span class="text-red-500 text-xs italic">
                                @error('status')
                                    {{$message}}
                                @enderror
                            </span>
                        </div>
                    </div>
                </div>

                <x-button-primary wire:click="saveQuiniela" class="w-fit mt-8 ml-auto !h-fit !p-3 my-auto">
                    Guardar cambios
                </x-button-primary>
            </div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 class="font-semibold text-xl text-primarycolor leading-tight mb-5">Partidos de la quiniela</h3>
            @foreach($matches as $match)
                <div class="w-full flex gap-6 md:flex-row mb-3">
                    <div class="w-1/4">
                        <div class="flex flex-col">
                            <p class="text-gray-800">Equipo local:</p>
                            <x-input wire:model="matches.{{ $loop->index }}.home_team" type="text" class="  !w-full"/>
                            <div>
                                <span class="text-red-500 text-xs italic">
                                    @error('matches.' . $loop->index . '.home_team')
                                        {{$message}}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="w-1/4">
                        <div class="flex flex-col">
                            <p class="text-gray-800">Equipo visitante:</p>
                            <x-input wire:model="matches.{{ $loop->index }}.away_team" type="text" class="  !w-full"/>
                            <div>
                                <span class="text-red-500 text-xs italic">
                                    @error('matches.' . $loop->index . '.away_team')
                                        {{$message}}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="w-1/4">
                        <div class="flex flex-col">
                            <p class="text-gray-800">Fecha y hora del partido:</p>
                            <x-input wire:model="matches.{{ $loop->index }}.match_datetime" type="datetime-local" class="  !w-full"/>
                            <div>
                                <span class="text-red-500 text-xs italic">
                                    @error('matches.' . $loop->index . '.match_datetime')
                                        {{$message}}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
</div>

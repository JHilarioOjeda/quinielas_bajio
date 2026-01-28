<div>

    <x-loading functionsList="addMatch, removeMatch, saveGeneralDataQuiniela, saveMatchesQuiniela" />

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edición de Quiniela
        </h2>
    </x-slot>

    <div class="py-12 space-y-8">
        <!-- Datos generales de la quiniela -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 class="font-semibold text-xl text-primarycolor leading-tight mb-5">Información de la Quiniela</h3>

            <div class="w-full flex flex-col md:flex-row gap-6 mb-3">
                <div class="w-full md:w-1/3">
                    <div class="flex flex-col">
                        <p class="text-gray-800">Título:</p>
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
                <div class="w-full md:w-1/3">
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
                <div class="gap-6 flex flex-col md:flex-row w-full">
                    <div class="w-full md:w-1/3">
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
                    <div class="w-full md:w-1/3">
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
                <div class="gap-6 flex flex-col md:flex-row w-full">
                    <div class="w-full md:w-1/3">
                        <div class="flex flex-col">
                            <p class="text-gray-800">Fecha de inicio:</p>
                            <x-input wire:model="sales_start" type="datetime-local" class="  !w-full"/>
                            <div>
                                <span class="text-red-500 text-xs italic">
                                    @error('sales_start')
                                        {{$message}}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="w-full md:w-1/3">
                        <div class="flex flex-col">
                            <p class="text-gray-800">Fecha de fin:</p>
                            <x-input wire:model="sales_end" type="datetime-local" class="  !w-full"/>
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

            <div class="w-full flex flex-col sm:flex-row sm:items-end gap-4">
                <div class="w-full sm:w-1/3">
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

                <x-button-primary wire:click="saveGeneralDataQuiniela" class="w-full sm:w-fit sm:ml-auto !h-fit !p-2 sm:mt-0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 mr-1">
                        <path d="M12 1.5a.75.75 0 0 1 .75.75V7.5h-1.5V2.25A.75.75 0 0 1 12 1.5ZM11.25 7.5v5.69l-1.72-1.72a.75.75 0 0 0-1.06 1.06l3 3a.75.75 0 0 0 1.06 0l3-3a.75.75 0 1 0-1.06-1.06l-1.72 1.72V7.5h3.75a3 3 0 0 1 3 3v9a3 3 0 0 1-3 3h-9a3 3 0 0 1-3-3v-9a3 3 0 0 1 3-3h3.75Z" />
                    </svg>

                    Guardar
                </x-button-primary>
            </div>
        </div>

        <!-- Datos de partidos -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex items-center mb-5">
                <h3 class="font-semibold text-xl text-primarycolor leading-tight">Partidos de la quiniela</h3>
            </div>
            @foreach($matches as $index => $match)
                <div class="w-full bg-gray-50 border border-gray-200 rounded-lg p-4 shadow-sm flex flex-col lg:flex-row lg:bg-transparent lg:border-0 lg:rounded-none lg:p-0 lg:shadow-none gap-4 lg:gap-6 mb-6">
                    <div class="w-full lg:w-1/4">
                        <div class="flex flex-col">
                            <p class="text-gray-800">Equipo local:</p>
                            <div class="flex items-center gap-2">
                                <select wire:model="matches.{{ $index }}.home_team" class="inputcatalogues team-select !w-full" wire:change="getImageTeam({{ $index }}, $event.target.value, 'home_team_image')">
                                    <option value="">Selecciona un equipo</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}" @selected(isset($match['home_team']) && (string) $match['home_team'] === (string) $team->id)>
                                            {{ $team->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(isset($match['home_team_image']) && $match['home_team_image'])
                                    <img src="{{ Storage::url($match['home_team_image']) }}" alt="Logo {{ $match['home_team'] }}" class="w-8 h-8 object-contain ml-auto">
                                @else
                                    <div class="w-8 h-8 bg-gray-200 flex items-center justify-center p-1 rounded-lg ml-auto">
                                        <span class="text-xs text-gray-500">N/A</span>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <span class="text-red-500 text-xs italic">
                                    @error('matches.' . $loop->index . '.home_team')
                                        {{$message}}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-center lg:hidden">
                        <span class="text-xs font-bold text-gray-700 bg-white border border-gray-200 rounded-full px-3 py-1">VS</span>
                    </div>
                    <p class="hidden lg:block text-center font-bold text-xl text-gray-700">VS</p>
                    <div class="w-full lg:w-1/4">
                        <div class="flex flex-col">
                            <p class="text-gray-800 text-left lg:text-right">Equipo visitante:</p>
                            <div class="flex items-center gap-2">
                                @if(isset($match['away_team_image']) && $match['away_team_image'])
                                    <img src="{{ Storage::url($match['away_team_image']) }}" alt="Logo {{ $match['away_team'] }}" class="w-8 h-8 object-contain mr-auto">
                                @else
                                    <div class="w-8 h-8 bg-gray-200 flex items-center justify-center p-1 rounded-lg mr-auto">
                                        <span class="text-xs text-gray-500">N/A</span>
                                    </div>
                                @endif
                                <select wire:model="matches.{{ $index }}.away_team" class="inputcatalogues team-select ml-auto w-full" wire:change="getImageTeam({{ $index }}, $event.target.value, 'away_team_image')">
                                    <option value="">Selecciona un equipo</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}" @selected(isset($match['away_team']) && (string) $match['away_team'] === (string) $team->id)>
                                            {{ $team->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <span class="text-red-500 text-xs italic">
                                    @error('matches.' . $loop->index . '.away_team')
                                        {{$message}}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="w-full lg:w-1/4 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
                        <div class="flex flex-col w-full">
                            <p class="text-gray-800">Fecha y hora del partido:</p>
                            <x-input wire:model="matches.{{ $index }}.match_datetime" type="datetime-local" class="  !w-full"/>
                            <div>
                                <span class="text-red-500 text-xs italic">
                                    @error('matches.' . $loop->index . '.match_datetime')
                                        {{$message}}
                                    @enderror
                                </span>
                            </div>
                        </div>

                        <x-buttondelete wire:click="removeMatch({{ $index }})" class="w-fit self-end sm:self-auto sm:w-fit !h-fit !p-0.5 sm:!p-1" title="Eliminar partido">
                        </x-buttondelete>
                    </div>
                </div>
            @endforeach
            <div class="flex items-center mb-7">
                <x-secondary-button wire:click="addMatch" class="ml-auto !h-fit !py-2 !px-4 text-sm">
                    + Agregar partido
                </x-secondary-button>
            </div>

            <div class="mt-5">
                <h4 class="font-semibold text-primarycolor leading-tight mb-2">
                    Partido suplente
                </h4>
                <div class="w-full bg-gray-50 border border-gray-200 rounded-lg p-4 shadow-sm flex flex-col lg:flex-row lg:bg-transparent lg:border-0 lg:rounded-none lg:p-0 lg:shadow-none gap-4 lg:gap-6 mb-3">
                    <div class="w-full lg:w-1/4">
                        <div class="flex flex-col">
                            <p class="text-gray-800">Equipo local:</p>
                            <div class="flex items-center gap-2">
                                <select wire:model="substitudematch_hometeam" class="inputcatalogues team-select !w-full" wire:change="getImageSubstituteTeam($event.target.value, 'substitudematch_hometeam_image')">
                                    <option value="">Selecciona un equipo</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}" @selected(isset($substitudematch_hometeam) && (string) $substitudematch_hometeam === (string) $team->id)>
                                            {{ $team->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if($substitudematch_hometeam_image)
                                    <img src="{{ Storage::url($substitudematch_hometeam_image) }}" alt="Logo {{ $substitudematch_hometeam }}" class="w-8 h-8 object-contain ml-auto">
                                @else
                                    <div class="w-8 h-8 bg-gray-200 flex items-center justify-center p-1 rounded-lg ml-auto">
                                        <span class="text-xs text-gray-500">N/A</span>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <span class="text-red-500 text-xs italic">
                                    @error('substitudematch_hometeam')
                                        {{$message}}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-center lg:hidden">
                        <span class="text-xs font-bold text-gray-700 bg-white border border-gray-200 rounded-full px-3 py-1">VS</span>
                    </div>
                    <p class="hidden lg:block text-center font-bold text-xl text-gray-700">VS</p>
                    <div class="w-full lg:w-1/4">
                        <div class="flex flex-col">
                            <p class="text-gray-800 text-left lg:ml-auto">Equipo visitante:</p>
                            <div class="flex items-center gap-2">
                                @if($substitudematch_awayteam_image)
                                    <img src="{{ Storage::url($substitudematch_awayteam_image) }}" alt="Logo {{ $substitudematch_awayteam }}" class="w-8 h-8 object-contain mr-auto">
                                @else
                                    <div class="w-8 h-8 bg-gray-200 flex items-center justify-center p-1 rounded-lg mr-auto">
                                        <span class="text-xs text-gray-500">N/A</span>
                                    </div>
                                @endif
                                <select wire:model="substitudematch_awayteam" class="inputcatalogues team-select !w-full" wire:change="getImageSubstituteTeam($event.target.value, 'substitudematch_awayteam_image')">
                                    <option value="">Selecciona un equipo</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}" @selected(isset($substitudematch_awayteam) && (string) $substitudematch_awayteam === (string) $team->id)>
                                            {{ $team->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <span class="text-red-500 text-xs italic">
                                    @error('substitudematch_awayteam')
                                        {{$message}}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="w-full lg:w-1/4 flex flex-col">
                        <div class="flex flex-col w-full">
                            <p class="text-gray-800">Fecha y hora del partido:</p>
                            <x-input wire:model="substitudematch_datetime" type="datetime-local" class="  !w-full"/>
                            <div>
                                <span class="text-red-500 text-xs italic">
                                    @error('substitudematch_datetime')
                                        {{$message}}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full flex mt-4">
                <x-button-primary wire:click="saveMatchesQuiniela" class="ml-auto !h-fit !py-2 !px-4 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 mr-1">
                        <path d="M12 1.5a.75.75 0 0 1 .75.75V7.5h-1.5V2.25A.75.75 0 0 1 12 1.5ZM11.25 7.5v5.69l-1.72-1.72a.75.75 0 0 0-1.06 1.06l3 3a.75.75 0 0 0 1.06 0l3-3a.75.75 0 1 0-1.06-1.06l-1.72 1.72V7.5h3.75a3 3 0 0 1 3 3v9a3 3 0 0 1-3 3h-9a3 3 0 0 1-3-3v-9a3 3 0 0 1 3-3h3.75Z" />
                    </svg>
                    Guardar partidos
                </x-button-primary>
            </div>
        </div>
    </div>
    
</div>

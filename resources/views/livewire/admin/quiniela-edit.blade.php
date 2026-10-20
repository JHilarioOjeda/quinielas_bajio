<div>

    <x-loading functionsList="addMatch, removeMatch, saveGeneralDataQuiniela, saveMatchesQuiniela, finalizeQuiniela" />

    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-white leading-tight">
            Edición de Quiniela
        </h2>
    </x-slot>

    <div class="py-8 sm:py-12 space-y-6 sm:space-y-8">
        <!-- Datos generales de la quiniela -->
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-gray-200 p-4 sm:p-6 lg:p-8">
            <h3 class="font-semibold text-xl text-primarycolor leading-tight mb-5 pb-3 border-b border-gray-200">Información de la quiniela</h3>

            <div class="w-full flex flex-col md:flex-row gap-5 md:gap-6 mb-5">
                <div class="w-full md:w-1/3">
                    <div class="flex flex-col">
                        <p class="text-base font-semibold text-gray-800 mb-1">Título:</p>
                        <x-input wire:model="title" type="text" class="  !w-full"/>
                        <div>
                            <span class="text-red-500 text-sm italic">
                                @error('title')
                                    {{$message}}
                                @enderror
                            </span>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/3">
                    <div class="flex flex-col">
                        <p class="text-base font-semibold text-gray-800 mb-1">Precio:</p>
                        <x-input wire:model="price" type="number" class="  !w-full"/>
                        <div>
                            <span class="text-red-500 text-sm italic">
                                @error('price')
                                    {{$message}}
                                @enderror
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full flex-col mb-5">
                <p class="text-base font-semibold text-primarycolor mb-2">Fechas de partidos</p>
                <div class="gap-5 md:gap-6 flex flex-col md:flex-row w-full">
                    <div class="w-full md:w-1/3">
                        <div class="flex flex-col">
                            <p class="text-base text-gray-800 mb-1">Fecha de inicio:</p>
                            <x-input wire:model="play_start" type="date" class="  !w-full"/>
                            <div>
                                <span class="text-red-500 text-sm italic">
                                    @error('play_start')
                                        {{$message}}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="w-full md:w-1/3">
                        <div class="flex flex-col">
                            <p class="text-base text-gray-800 mb-1">Fecha de fin:</p>
                            <x-input wire:model="play_end" type="date" class="  !w-full"/>
                            <div>
                                <span class="text-red-500 text-sm italic">
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
                <p class="text-base font-semibold text-primarycolor mb-2">Fecha de ventas</p>
                <div class="gap-5 md:gap-6 flex flex-col md:flex-row w-full">
                    <div class="w-full md:w-1/3">
                        <div class="flex flex-col">
                            <p class="text-base text-gray-800 mb-1">Fecha de inicio:</p>
                            <x-input wire:model="sales_start" type="datetime-local" class="  !w-full"/>
                            <div>
                                <span class="text-red-500 text-sm italic">
                                    @error('sales_start')
                                        {{$message}}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="w-full md:w-1/3">
                        <div class="flex flex-col">
                            <p class="text-base text-gray-800 mb-1">Fecha de fin:</p>
                            <x-input wire:model="sales_end" type="datetime-local" class="  !w-full"/>
                            <div>
                                <span class="text-red-500 text-sm italic">
                                    @error('sales_end')
                                        {{$message}}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="w-full flex flex-col gap-4 pt-4 border-t border-gray-200">
                <div class="w-full sm:w-1/2 lg:w-1/3">
                    <div class="flex flex-col">
                        <p class="text-base font-semibold text-gray-800 mb-1">Estatus de quiniela:</p>
                        <p class="text-sm italic text-primarycolor mb-1">Cambia el estatus de la quiniela para controlar su disponibilidad.</p>
                        <select name="status" id="status" wire:model="status" class="inputcatalogues !w-full">
                            <option value="open">Abierta</option>
                            <option value="closed">Cerrada</option>
                            <option value="finished" disabled>Finalizada</option>
                        </select>
                        <div>
                            <span class="text-red-500 text-sm italic">
                                @error('status')
                                    {{$message}}
                                @enderror
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <x-button-primary wire:click="saveGeneralDataQuiniela" class="w-full sm:w-fit justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 flex-shrink-0">
                            <path d="M12 1.5a.75.75 0 0 1 .75.75V7.5h-1.5V2.25A.75.75 0 0 1 12 1.5ZM11.25 7.5v5.69l-1.72-1.72a.75.75 0 0 0-1.06 1.06l3 3a.75.75 0 0 0 1.06 0l3-3a.75.75 0 1 0-1.06-1.06l-1.72 1.72V7.5h3.75a3 3 0 0 1 3 3v9a3 3 0 0 1-3 3h-9a3 3 0 0 1-3-3v-9a3 3 0 0 1 3-3h3.75Z" />
                        </svg>
                        Guardar
                    </x-button-primary>

                    @if($status === 'closed')
                    <x-buttondesact onclick="confirmFinishQuiniela()" class="w-full sm:w-fit justify-center">
                        Finalizar quiniela
                    </x-buttondesact>

                    <a href="{{ route('admin.lista-final') }}" target="_blank"
                       class="inline-flex items-center justify-center gap-2 min-h-11 px-5 py-2.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-sm font-semibold shadow transition ease-in-out duration-150 w-full sm:w-fit">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 flex-shrink-0">
                            <path fill-rule="evenodd" d="M5.625 1.5H9a3.75 3.75 0 0 1 3.75 3.75v1.875c0 1.036.84 1.875 1.875 1.875H16.5a3.75 3.75 0 0 1 3.75 3.75v7.875c0 1.035-.84 1.875-1.875 1.875H5.625a1.875 1.875 0 0 1-1.875-1.875V3.375c0-1.036.84-1.875 1.875-1.875Zm5.845 17.03a.75.75 0 0 0 1.06 0l3-3a.75.75 0 1 0-1.06-1.06l-1.72 1.72V12a.75.75 0 0 0-1.5 0v4.19l-1.72-1.72a.75.75 0 0 0-1.06 1.06l3 3Z" clip-rule="evenodd" />
                            <path d="M14.25 5.25a5.23 5.23 0 0 0-1.279-3.434 9.768 9.768 0 0 1 6.963 6.963A5.23 5.23 0 0 0 16.5 7.5h-1.875a.375.375 0 0 1-.375-.375V5.25Z" />
                        </svg>
                        Lista Final
                    </a>
                    @endif
                </div>
            </div>
        </div>
        </div>

        <!-- Datos de partidos -->
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-gray-200 p-4 sm:p-6 lg:p-8">
            <div class="flex items-center mb-5 pb-3 border-b border-gray-200">
                <h3 class="font-semibold text-xl text-primarycolor leading-tight">Partidos de la quiniela</h3>
            </div>
            @foreach($matches as $index => $match)
                <div class="w-full bg-gray-50 border border-gray-200 rounded-lg p-4 shadow-sm flex flex-col lg:flex-row lg:bg-transparent lg:border-0 lg:rounded-none lg:p-0 lg:shadow-none gap-4 lg:gap-6 mb-6">
                    <div class="w-full lg:w-1/4">
                        <div class="flex flex-col">
                            <p class="text-base font-semibold text-gray-800 mb-1">Equipo local:</p>
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
                            <p class="text-base font-semibold text-gray-800 mb-1 text-left lg:text-right">Equipo visitante:</p>
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
                            <p class="text-base font-semibold text-gray-800 mb-1">Fecha y hora del partido:</p>
                            <x-input wire:model="matches.{{ $index }}.match_datetime" type="datetime-local" class="  !w-full"/>
                            <div>
                                <span class="text-red-500 text-xs italic">
                                    @error('matches.' . $loop->index . '.match_datetime')
                                        {{$message}}
                                    @enderror
                                </span>
                            </div>
                        </div>

                        <x-buttondelete wire:click="removeMatch({{ $index }})" class="w-full sm:w-fit" title="Eliminar partido">
                        </x-buttondelete>
                    </div>
                </div>
            @endforeach
            <div class="flex items-center mb-7">
                <x-secondary-button wire:click="addMatch" class="ml-auto">
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
                            <p class="text-base font-semibold text-gray-800 mb-1">Equipo local:</p>
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
                            <p class="text-base font-semibold text-gray-800 mb-1 text-left lg:ml-auto">Equipo visitante:</p>
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
                            <p class="text-base font-semibold text-gray-800 mb-1">Fecha y hora del partido:</p>
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
                <x-button-primary wire:click="saveMatchesQuiniela" class="w-full sm:w-fit sm:ml-auto justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 flex-shrink-0">
                        <path d="M12 1.5a.75.75 0 0 1 .75.75V7.5h-1.5V2.25A.75.75 0 0 1 12 1.5ZM11.25 7.5v5.69l-1.72-1.72a.75.75 0 0 0-1.06 1.06l3 3a.75.75 0 0 0 1.06 0l3-3a.75.75 0 1 0-1.06-1.06l-1.72 1.72V7.5h3.75a3 3 0 0 1 3 3v9a3 3 0 0 1-3 3h-9a3 3 0 0 1-3-3v-9a3 3 0 0 1 3-3h3.75Z" />
                    </svg>
                    Guardar partidos
                </x-button-primary>
            </div>
        </div>
        </div>
    </div>

</div>

<script>
    function confirmFinishQuiniela(){
        Swal.fire({
            title: '¿Seguro que deseas finalizar la quiniela?',
            text: 'Se archivaran los tickets actuales y los resultados de los partidos.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#408331',
            cancelButtonColor: '#e02424',
            confirmButtonText: 'Sí, finalizar y archivar tickets',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
              Livewire.dispatch('finishQuiniela');
            }
        })
    }
</script>

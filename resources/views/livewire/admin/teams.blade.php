<div>
     <x-slot name="header">
        <h2 class="font-semibold text-2xl text-white leading-tight">
            Administración de equipos
        </h2>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-gray-200">
                <div class="p-4 sm:p-6 lg:p-8 bg-white">
                    <div class="flex w-full flex-col gap-3 sm:flex-row sm:items-center mb-5">
                        <x-search-input wireModel="search" placeholder="Buscar equipo..."  class="w-full sm:w-1/2"/>

                        <x-button-primary class="w-full sm:w-auto sm:ml-auto justify-center" wire:click="scmodalTeam(0)">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 flex-shrink-0">
                                <path fill-rule="evenodd" d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                            </svg>
                            Registrar equipo
                        </x-button-primary>
                    </div>

                    @if($teams->isEmpty())
                        <div class="rounded-lg border border-dashed border-gray-300 py-10 text-center text-base text-gray-500">
                            No hay equipos registrados.
                        </div>
                    @else
                        <!-- Tarjetas (móvil) -->
                        <div class="space-y-3 md:hidden">
                            @foreach($teams as $team)
                                <div class="rounded-xl border border-gray-200 shadow-sm p-4 flex items-center gap-4">
                                    @if($team->logo)
                                        <img src="{{ Storage::url($team->logo) }}" alt="Logo" class="h-14 w-14 object-contain flex-shrink-0">
                                    @else
                                        <div class="h-14 w-14 bg-gray-100 rounded flex items-center justify-center text-xs text-gray-400 flex-shrink-0">N/A</div>
                                    @endif
                                    <div class="min-w-0 flex-1">
                                        <p class="font-semibold text-lg text-gray-900 truncate">{{ $team->name }}</p>
                                        <p class="text-base text-gray-500">{{ $team->short_name }}</p>
                                    </div>
                                    <div class="flex flex-col gap-2 flex-shrink-0">
                                        <x-buttonedit wire:click="scmodalTeam({{ $team->id }})" />
                                        <x-buttondelete onclick="confirmDeleteTeam('{{ $team->id }}')" />
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Tabla (escritorio) -->
                        <div class="hidden md:block mt-2 rounded-lg border border-gray-200 max-h-[80vh] w-full overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-base">
                                <thead class="bg-primarycolor text-white">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-semibold">Nombre</th>
                                        <th class="px-4 py-3 text-left font-semibold">Nombre corto</th>
                                        <th class="px-4 py-3 font-semibold">Logo</th>
                                        <th class="px-4 py-3"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($teams as $team)
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $team->name }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $team->short_name }}</td>
                                        <td class="px-4 py-3">
                                            @if($team->logo)
                                                <img src="{{ Storage::url($team->logo) }}" alt="Logo" class="h-12 w-12 object-contain mx-auto">
                                            @else
                                                <span class="text-gray-400 text-sm block text-center">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center justify-center gap-2">
                                                <x-buttonedit wire:click="scmodalTeam({{ $team->id }})" />
                                                <x-buttondelete onclick="confirmDeleteTeam('{{ $team->id }}')" />
                                            </div>
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

    <div class="fixed inset-0 top-20 @if(!$modalTeam) hidden @endif left-0 z-50 max-h-full overflow-y-auto">
        <div class="flex justify-center items-center  bg-gray-800 antialiased top-0 opacity-70 left-0 z-30 w-full h-full fixed "></div>

        <div class="flex text-gray-500 text:md justify-center items-center antialiased top-0 left-0 z-40 w-full h-full fixed">
            <div class="flex flex-col w-full sm:w-11/12 md:w-8/12 lg:w-5/12 mx-4 sm:mx-auto rounded-xl overflow-y-auto bg-white px-5 sm:px-6 py-4" style="max-height: 90%;">
                <div class="flex flex-row justify-between items-start gap-3 rounded-tl-lg rounded-tr-lg">
                    <p class="text-xl sm:text-2xl w-fit font-semibold text-primarycolor border-b-2 border-b-primarycolor pb-1">
                        @if($teamSelected != null)
                            Editar equipo: {{ $teamSelected->name }}
                        @else
                            Registrar equipo
                        @endif
                    </p>
                    <button wire:click="scmodalTeam(0)" aria-label="Cerrar" class="flex-shrink-0 inline-flex items-center justify-center min-h-11 min-w-11 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600">
                        <svg  class="w-6 h-6"  fill="none"
                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="flex flex-col mt-5">
                    <!-- Formulario para agregar equipo -->
                    <div class="flex w-full flex-col gap-4 mb-4 md:flex-row md:space-x-4 md:gap-0">
                        <div class="w-full md:w-1/2">
                            <x-label value="Nombre del equipo" class="text-base font-semibold text-gray-800" />
                            <x-input type="text" wire:model="name" class="w-full" />
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="w-full md:w-1/2">
                            <x-label value="Nombre corto" class="text-base font-semibold text-gray-800" />
                            <x-input type="text" wire:model="short_name" class="w-full" />
                            @error('short_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="flex w-full flex-col gap-4 mb-4 md:flex-row md:space-x-4 md:gap-0">
                        <div class="w-full md:w-1/2">
                            <x-file-input id="logo" label="Logo del equipo" wire:model="logo" accept="image/*"/>
                            @error('logo')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="w-full md:w-1/2">
                        @if($logo)
                            <div class="mt-3 flex items-center space-x-3">
                                @if (is_string($logo))
                                    <img src="{{ Storage::url($logo) }}" alt="Logo actual" class="w-32 h-32 md:w-52 md:h-52 rounded object-contain mx-auto" />
                                @elseif (method_exists($logo, 'temporaryUrl'))
                                    <img src="{{ $logo->temporaryUrl() }}" alt="Logo seleccionado" class="w-32 h-32 md:w-52 md:h-52 rounded object-contain mx-auto" />
                                @endif
                            </div>
                        @endif
                        </div>
                    </div>
                    <x-button-primary wire:click="saveTeam" class="w-full sm:w-fit mt-2 sm:ml-auto justify-center">
                        @if($teamSelected != null)
                            Actualizar
                        @else
                            Registrar
                        @endif
                    </x-button-primary>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDeleteTeam(idTeam){
        Swal.fire({
            title: '¿Seguro que deseas eliminar este equipo?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#408331',
            cancelButtonColor: '#e02424',
            confirmButtonText: 'Si, eliminar',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
              Livewire.dispatch('deleteTeam', {idTeam: idTeam});
            }
        })
    }
</script>

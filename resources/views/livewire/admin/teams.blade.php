<div>
     <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Administración de equipos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <div class="flex w-full flex-col gap-3 sm:flex-row sm:items-center">
                        <x-search-input wireModel="search" placeholder="Buscar equipo..."  class="w-full sm:w-1/2"/>

                        <x-button-primary class="w-full sm:w-auto sm:ml-auto justify-center" wire:click="scmodalTeam(0)">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 mr-2">
                                <path fill-rule="evenodd" d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                            </svg>
                            Registrar equipo
                        </x-button-primary>
                    </div>


                    <div class="mt-6 rounded max-h-[80vh] w-full overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-primarycolor text-white">
                                <tr>
                                    <th class="px-3 py-2 text-left">Nombre</th>
                                    <th class="px-3 py-2 text-left">Nombre corto</th>
                                    <th class="px-3 py-2">Logo</th>
                                    <th class="px-3 py-2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($teams as $team)
                                <tr>
                                    <td class="border px-3 py-2">{{ $team->name }}</td>
                                    <td class="border px-3 py-2">{{ $team->short_name }}</td>
                                    <td class="border px-3 py-2">
                                        @if($team->logo)
                                            <img src="{{ Storage::url($team->logo) }}" alt="Logo" class="h-12 w-12 object-contain mx-auto">
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="border px-3 py-2">
                                        <div class="flex flex-col items-stretch gap-2 justify-center sm:flex-row sm:items-center sm:justify-center">
                                            <x-buttonedit class="w-full sm:w-auto" wire:click="scmodalTeam({{ $team->id }})" />
                                            <x-buttondelete class="w-full sm:w-auto" onclick="confirmDeleteTeam('{{ $team->id }}')" />
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @if($teams->isEmpty())
                                <tr>
                                    <td colspan="4" class="border px-3 py-2 text-center">No hay equipos registrados.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="fixed inset-0 top-20 @if(!$modalTeam) hidden @endif left-0 z-50 max-h-full overflow-y-auto">
        <div class="flex justify-center items-center  bg-gray-800 antialiased top-0 opacity-70 left-0 z-30 w-full h-full fixed "></div>
        
        <div class="flex text-gray-500 text:md justify-center items-center antialiased top-0 left-0 z-40 w-full h-full fixed">
            <div class="flex flex-col w-full sm:w-11/12 md:w-8/12 lg:w-5/12 mx-4 sm:mx-auto rounded-lg overflow-y-auto bg-white px-6 py-3" style="max-height: 90%;">
                <div class="flex flex-row justify-between rounded-tl-lg rounded-tr-lg">
                    <p class="text-2xl w-fit my-auto font-semibold text-primarycolor border-b-2 border-b-primarycolor">
                        @if($teamSelected != null)
                            Editar datos del equipo: {{ $teamSelected->name }}
                        @else
                            Registrar equipo
                        @endif
                    </p>
                    <button wire:click="scmodalTeam(0)" class="closebttn">
                        <svg  class="w-6 h-6 text-white"  fill="none"
                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="flex flex-col mt-5">
                    <!-- Formulario para agregar equipo -->
                    <div class="flex w-full flex-col gap-4 mb-4 md:flex-row md:space-x-4 md:gap-0">
                        <div class="w-full md:w-1/2">
                            <x-label value="Nombre del equipo" />
                            <x-input type="text" wire:model="name" class="w-full" />
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="w-full md:w-1/2">
                            <x-label value="Nombre corto" />
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
                    <x-button-primary wire:click="saveTeam" class="w-fit mt-4 ml-auto !py-3">
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

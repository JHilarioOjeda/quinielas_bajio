<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Team;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Auth;
use DB;
use Log;
use Http;
use Throwable;



class Teams extends Component
{
    use WithFileUploads;

    public $search = '', $modalTeam = false, $teamSelected = null;

    public $name, $short_name, $logo;

    protected $rules = [];
    protected $validationAttributes  = [
        'name' => 'Nombre',
        'short_name' => 'Abreviación',
        'logo' => 'Logo del equipo',
    ];

    protected $listeners = ['deleteTeam'];

    public function render()
    {
        $teams = Team::where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('short_name', 'like', '%' . $this->search . '%')
                      ->get();

        return view('livewire.admin.teams', compact('teams'));
    }


    public function scmodalTeam($idTeam){
        if ($this->modalTeam == true) {
            $this->modalTeam = false;
            $this->teamSelected = null;
            $this->reset(['name', 'short_name', 'logo']);
        } else {
            $this->modalTeam = true;
            if($idTeam != 0){
                $this->teamSelected = Team::find($idTeam);
                if($this->teamSelected != null){
                    $this->name = $this->teamSelected->name;
                    $this->short_name = $this->teamSelected->short_name;
                    $this->logo = $this->teamSelected->logo;
                }
            }
        }
    }

    public function saveTeam(){

         $this->rules +=[
            'name' => 'required|string|max:100',
            'short_name' => 'required|string|max:20',
            'logo' => 'nullable|max:2048',
        ];
        $this->validate();

        try {
            if($this->teamSelected != null){
                $team = $this->teamSelected;
            } else {
                $team = new Team();
            }

            $team->name = $this->name;
            $team->short_name = $this->short_name;

            if ($this->logo && is_object($this->logo)) {
                $logoPath = $this->logo->store('teams', 'public');
                $team->logo = $logoPath;
            }

            $team->save();

            $message = $this->teamSelected != null ? 'actualizó' : 'registró';

            LivewireAlert::title('Éxito')
                    ->text("Equipo $message exitosamente")
                    ->success()
                    ->show();

            $this->scmodalTeam(0);
        } catch (Throwable $e) {
            Log::error('Error saving team: ' . $e->getMessage());
            LivewireAlert::title('Error')
                    ->text('Ocurrió un error al guardar el equipo. Por favor, inténtelo de nuevo.')
                    ->error()
                    ->show();
        }
    }

    public function deleteTeam($idTeam){
        try {
            $team = Team::find($idTeam);
            if ($team) {
                $team->delete();

                LivewireAlert::title('Éxito')
                        ->text("Equipo eliminado exitosamente")
                        ->success()
                        ->show();
            } else {
                LivewireAlert::title('Error')
                        ->text('El equipo no fue encontrado.')
                        ->error()
                        ->show();
            }
        } catch (Throwable $e) {
            Log::error('Error deleting team: ' . $e->getMessage());
            LivewireAlert::title('Error')
                    ->text('Ocurrió un error al eliminar el equipo. Por favor, inténtelo de nuevo.')
                    ->error()
                    ->show();
        }
    }

}

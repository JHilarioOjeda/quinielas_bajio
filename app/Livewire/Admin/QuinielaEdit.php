<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Auth;
use DB;
use Log;
use Http;
use Throwable;
use App\Models\QuinielaEvent;
use App\Models\Team;


class QuinielaEdit extends Component
{

    use WithFileUploads;

    public $matches = [
        [
            'home_team' => '',
            'away_team' => '',
            'match_datetime' => '',
        ],
    ], $quinielaEvent;


    public function removeMatch($index)
    {
        if (! is_array($this->matches)) {
            return;
        }

        unset($this->matches[$index]);

        // Reindexar para que los índices coincidan con $loop->index en la vista
        $this->matches = array_values($this->matches);
    }


    public function addMatch()
    {
        $this->matches[] = [
            'home_team' => '',
            'away_team' => '',
            'match_datetime' => '',
        ];
    }


    public function render()
    {
        $teams = Team::orderBy('name')->get();

        return view('livewire.admin.quiniela-edit', compact('teams'));
    }
}

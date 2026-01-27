<?php

namespace App\Livewire;

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
use App\Models\EventMatch;
use App\Models\MatchGame;
use Carbon\Carbon;

class GeneralQuiniela extends Component
{
    /**
     * Selección por partido: [event_match_id => 'H'|'D'|'A']
     */
    public array $picks = [];

    public function render(){
        $quiniela = QuinielaEvent::with([
            'eventMatches.matchGame.homeTeam',
            'eventMatches.matchGame.awayTeam',
        ])->first();
        return view('livewire.general-quiniela', ['quiniela' => $quiniela]);
    }
}

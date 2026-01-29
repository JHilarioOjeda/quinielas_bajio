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
use App\Models\EventMatch;
use App\Models\MatchGame;
use Carbon\Carbon;


class QuinielaEdit extends Component
{

    use WithFileUploads;

    public $quinielaEvent;
    public $title, $price, $play_start, $play_end, $sales_start, $sales_end, $status = 'open';

    public $substitudematch_hometeam, $substitudematch_awayteam, $substitudematch_datetime;
    public $substitudematch_hometeam_image = '';
    public $substitudematch_awayteam_image = '';

    public $matches = [
        [
            'home_team' => '',
            'home_team_image' => '',
            'away_team' => '',
            'away_team_image' => '',
            'match_datetime' => '',
        ],
    ];

    protected $rules = [];
    protected $validationAttributes  = [
        'title' => 'título',
        'price' => 'precio',
        'play_start' => 'inicio de juego',
        'play_end' => 'fin de juego',
        'sales_start' => 'inicio de ventas',
        'sales_end' => 'fin de ventas',
        'status' => 'estado',
    ];

    protected $listeners = [
        'finishQuiniela' => 'finalizeQuiniela',
    ];

    public function mount(){
        $this->quinielaEvent = QuinielaEvent::first();
        if ($this->quinielaEvent) {
            $this->title = $this->quinielaEvent->title;
            $this->price = $this->quinielaEvent->price;
            $this->play_start = $this->quinielaEvent->play_start;
            $this->play_end = $this->quinielaEvent->play_end;
            $this->sales_start = $this->quinielaEvent->sales_start;
            $this->sales_end = $this->quinielaEvent->sales_end;
            $this->status = $this->quinielaEvent->status;

            // Cargar partidos existentes del evento (si los hay)
            $this->matches = [];

            $eventMatches = $this->quinielaEvent->eventMatches()
                ->with('matchGame')
                ->orderBy('id')
                ->get();

            foreach ($eventMatches as $eventMatch) {
                if (! $eventMatch->matchGame) {
                    continue;
                }

                if ($eventMatch->is_substitute) {
                    $this->substitudematch_hometeam = $eventMatch->matchGame->home_team_id;
                    $this->substitudematch_awayteam = $eventMatch->matchGame->away_team_id;
                    $this->substitudematch_datetime = $eventMatch->matchGame->match_date
                        ? Carbon::parse($eventMatch->matchGame->match_date)->format('Y-m-d\TH:i')
                        : null;

                    if ($eventMatch->matchGame->homeTeam) {
                        $this->substitudematch_hometeam_image = $eventMatch->matchGame->homeTeam->logo;
                    }

                    if ($eventMatch->matchGame->awayTeam) {
                        $this->substitudematch_awayteam_image = $eventMatch->matchGame->awayTeam->logo;
                    }
                } else {
                    $this->matches[] = [
                        'home_team' => $eventMatch->matchGame->homeTeam->id,
                        'home_team_image' => $eventMatch->matchGame->homeTeam->logo,
                        'away_team' => $eventMatch->matchGame->awayTeam->id,
                        'away_team_image' => $eventMatch->matchGame->awayTeam->logo,
                        'match_datetime' => $eventMatch->matchGame->match_date
                            ? Carbon::parse($eventMatch->matchGame->match_date)->format('Y-m-d\TH:i')
                            : null,
                    ];

                }
            }

            if (empty($this->matches)) {
                $this->matches = [
                    [
                        'home_team' => '',
                        'home_team_image' => '',
                        'away_team' => '',
                        'away_team_image' => '',
                        'match_datetime' => '',
                    ],
                ];
            }
        }
    }

    public function render(){
        $teams = Team::orderBy('name')->get();

        return view('livewire.admin.quiniela-edit', compact('teams'));
    }

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
            'home_team_image' => '',
            'away_team' => '',
            'away_team_image' => '',
            'match_datetime' => '',
        ];
    }


    public function getImageTeam($index, $idTeam, $type){
        $teamSelected = Team::find($idTeam);
        if($teamSelected != null){
            $this->matches[$index][$type] = $teamSelected->logo;
        } else {
            $this->matches[$index][$type] = '';
        }
    }

    public function getImageSubstituteTeam($idTeam, $type){
        $teamSelected = Team::find($idTeam);
        if($teamSelected != null){
            $this->$type = $teamSelected->logo;
        } else {
            $this->$type = '';
        }
    }

    public function saveGeneralDataQuiniela()
    {
        $this->rules += [ 
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'play_start' => 'required|date',
            'play_end' => 'required|date|after:play_start',
            'sales_start' => 'required|date',
            'sales_end' => 'required|date|after:sales_start',
            'status' => 'required',
        ];
        $this->validate();

        try{

            $quinielaAux = ($this->quinielaEvent == null) ? new QuinielaEvent() : QuinielaEvent::find($this->quinielaEvent->id);

            $quinielaAux->title = $this->title;
            $quinielaAux->price = $this->price;
            $quinielaAux->play_start = $this->play_start;
            $quinielaAux->play_end = $this->play_end;
            $quinielaAux->sales_start = $this->sales_start;
            $quinielaAux->sales_end = $this->sales_end;
            $quinielaAux->status = $this->status;
            $quinielaAux->save();

            $this->quinielaEvent = $quinielaAux;

            LivewireAlert::title('Éxito')
                    ->text('Información general de la quiniela guardada correctamente.')
                    ->success()
                    ->show();

        }catch(Throwable $e){
            Log::error('Error al crar/actualizar la quiniela: ' . $e->getMessage());
            LivewireAlert::title('Error')
                    ->text('Ocurrió un error al guardar la información general de la quiniela. Por favor, inténtelo de nuevo.')
                    ->error()
                    ->show();
        }
    }   

    public function finalizeQuiniela()
    {
        if (! $this->quinielaEvent) {
            LivewireAlert::title('Error')
                ->text('No hay una quiniela activa para finalizar.')
                ->error()
                ->show();
            return;
        }

        try {
            DB::transaction(function () {
                $this->quinielaEvent->status = 'finished';
                $this->quinielaEvent->save();

                $this->quinielaEvent->tickets()->update(['active' => false]);

                $eventMatches = $this->quinielaEvent->eventMatches()->with('matchGame')->get();

                foreach ($eventMatches as $eventMatch) {
                    if ($eventMatch->matchGame) {
                        $eventMatch->matchGame->update([
                            'home_score' => null,
                            'away_score' => null,
                            'status' => 'pending',
                        ]);
                    }
                }
            });

            $this->status = 'finished';

            LivewireAlert::title('Éxito')
                ->text('La quiniela se finalizó, los tickets fueron archivados y los resultados de los partidos se limpiaron.')
                ->success()
                ->show();
        } catch (Throwable $e) {
            Log::error('Error al finalizar la quiniela: ' . $e->getMessage());

            LivewireAlert::title('Error')
                ->text('Ocurrió un error al finalizar la quiniela. Por favor, inténtalo de nuevo.')
                ->error()
                ->show();
        }
    }

    public function saveMatchesQuiniela()
    {
        $this->validate([
            'matches' => 'required|array|min:1',
            'matches.*.home_team' => 'required|integer|exists:teams,id',
            'matches.*.away_team' => 'required|integer|exists:teams,id',
            'matches.*.match_datetime' => 'required|date',
            'substitudematch_hometeam' => 'nullable|integer|exists:teams,id',
            'substitudematch_awayteam' => 'nullable|integer|exists:teams,id',
            'substitudematch_datetime' => 'nullable|date',
        ], [], [
            'matches.*.home_team' => 'equipo local',
            'matches.*.away_team' => 'equipo visitante',
            'matches.*.match_datetime' => 'fecha y hora del partido',
            'substitudematch_hometeam' => 'equipo local suplente',
            'substitudematch_awayteam' => 'equipo visitante suplente',
            'substitudematch_datetime' => 'fecha y hora del partido suplente',
        ]);

        foreach ($this->matches as $index => $match) {
            if (isset($match['home_team'], $match['away_team']) && $match['home_team'] === $match['away_team']) {
                $this->addError("matches.$index.home_team", 'El equipo local y visitante deben ser distintos.');
                $this->addError("matches.$index.away_team", 'El equipo local y visitante deben ser distintos.');
                return;
            }
        }

        if ($this->substitudematch_hometeam || $this->substitudematch_awayteam || $this->substitudematch_datetime) {
            if (! $this->substitudematch_hometeam || ! $this->substitudematch_awayteam || ! $this->substitudematch_datetime) {
                $this->addError('substitudematch_hometeam', 'Debes completar todos los datos del partido suplente.');
                $this->addError('substitudematch_awayteam', 'Debes completar todos los datos del partido suplente.');
                $this->addError('substitudematch_datetime', 'Debes completar todos los datos del partido suplente.');
                return;
            }

            if ($this->substitudematch_hometeam === $this->substitudematch_awayteam) {
                $this->addError('substitudematch_hometeam', 'El equipo local y visitante suplente deben ser distintos.');
                $this->addError('substitudematch_awayteam', 'El equipo local y visitante suplente deben ser distintos.');
                return;
            }
        }

        $quinielaEvent = $this->quinielaEvent ?? QuinielaEvent::first();

        if (! $quinielaEvent) {
            LivewireAlert::title('Error')
                ->text('Primero debes guardar la información general de la quiniela.')
                ->error()
                ->show();

            return;
        }

        try{
            DB::beginTransaction();

            $existingEventMatches = $quinielaEvent->eventMatches()->with('matchGame')->get();

            foreach ($existingEventMatches as $eventMatch) {
                if ($eventMatch->matchGame) {
                    $eventMatch->matchGame->delete();
                }
                $eventMatch->delete();
            }

            foreach ($this->matches as $matchData) {
                $matchDate = ! empty($matchData['match_datetime'])
                    ? Carbon::parse($matchData['match_datetime'])
                    : null;

                $match = MatchGame::create([
                    'home_team_id' => $matchData['home_team'],
                    'away_team_id' => $matchData['away_team'],
                    'match_date' => $matchDate,
                    'status' => 'pending',
                ]);

                EventMatch::create([
                    'quiniela_event_id' => $quinielaEvent->id,
                    'match_id' => $match->id,
                    'is_substitute' => false,
                ]);
            }

            if ($this->substitudematch_hometeam && $this->substitudematch_awayteam && $this->substitudematch_datetime) {
                $subDate = Carbon::parse($this->substitudematch_datetime);

                $subMatch = MatchGame::create([
                    'home_team_id' => $this->substitudematch_hometeam,
                    'away_team_id' => $this->substitudematch_awayteam,
                    'match_date' => $subDate,
                    'status' => 'pending',
                ]);

                EventMatch::create([
                    'quiniela_event_id' => $quinielaEvent->id,
                    'match_id' => $subMatch->id,
                    'is_substitute' => true,
                ]);
            }

            DB::commit();

            $this->quinielaEvent = $quinielaEvent->fresh('eventMatches');

            LivewireAlert::title('Éxito')
                    ->text('Partidos de la quiniela guardados correctamente.')
                    ->success()
                    ->show();

        }catch(Throwable $e){
            DB::rollBack();

            Log::error('Error al guardar los partidos de la quiniela: ' . $e->getMessage());
            LivewireAlert::title('Error')
                    ->text('Ocurrió un error al guardar los partidos de la quiniela. Por favor, inténtelo de nuevo.')
                    ->error()
                    ->show();
        }
    }
    
}

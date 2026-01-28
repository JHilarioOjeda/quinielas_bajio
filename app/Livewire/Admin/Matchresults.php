<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\QuinielaEvent;
use App\Models\MatchGame;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Throwable;

class Matchresults extends Component
{
    public $quiniela;
    public $selectedEventId;

    /**
     * Resultados por partido (match_id => [home_score, away_score, status]).
     */
    public array $results = [];

    /**
     * Partidos del evento seleccionado (colección para la vista).
     */
    public $eventMatches;

    protected $validationAttributes = [
        'selectedEventId' => 'quiniela',
    ];

    public function mount(): void
    {
        $this->quiniela = QuinielaEvent::first();
        $this->selectedEventId = $this->quiniela->id;
        $this->loadEventMatches();
    }

    public function updatedSelectedEventId(): void
    {
        $this->resetErrorBag();
        $this->results = [];
        $this->loadEventMatches();
    }

    protected function loadEventMatches(): void
    {
        $this->eventMatches = collect();

        if (! $this->selectedEventId) {
            return;
        }

        $quiniela = QuinielaEvent::with([
            'eventMatches.matchGame.homeTeam',
            'eventMatches.matchGame.awayTeam',
        ])->find($this->selectedEventId);

        if (! $quiniela) {
            return;
        }

        $this->eventMatches = $quiniela->eventMatches
            ->filter(fn ($em) => $em->matchGame)
            ->sortBy(fn ($em) => optional($em->matchGame)->match_date)
            ->values();

        foreach ($this->eventMatches as $eventMatch) {
            $match = $eventMatch->matchGame;

            $this->results[$match->id] = [
                'home_score' => $match->home_score,
                'away_score' => $match->away_score,
                'status' => $match->status ?? 'pending',
            ];
        }
    }

    protected function rules(): array
    {
        return [
            'selectedEventId' => 'required|integer|exists:quiniela_events,id',
            'results' => 'array',
            'results.*.home_score' => 'nullable|integer|min:0',
            'results.*.away_score' => 'nullable|integer|min:0',
            'results.*.status' => 'required|string|in:pending,finished',
        ];
    }

    public function saveMatch(int $matchId): void
    {
        $this->validate();

        if (! isset($this->results[$matchId])) {
            return;
        }

        $payload = $this->results[$matchId];

        if (($payload['status'] ?? 'pending') === 'finished') {
            if ($payload['home_score'] === null || $payload['away_score'] === null) {
                $this->addError("results.$matchId.home_score", 'Debes capturar ambos marcadores para finalizar el partido.');
                $this->addError("results.$matchId.away_score", 'Debes capturar ambos marcadores para finalizar el partido.');
                return;
            }
        }

        try {
            DB::transaction(function () use ($matchId, $payload) {
                /** @var MatchGame $match */
                $match = MatchGame::lockForUpdate()->findOrFail($matchId);

                $status = $payload['status'] ?? 'pending';

                if ($status === 'pending') {
                    $match->home_score = null;
                    $match->away_score = null;
                } else {
                    $match->home_score = $payload['home_score'];
                    $match->away_score = $payload['away_score'];
                }

                $match->status = $status;
                $match->save();
            });

            LivewireAlert::title('Éxito')
                ->text('Resultado guardado correctamente.')
                ->success()
                ->show();

            $this->loadEventMatches();
        } catch (Throwable $e) {
            Log::error('Error al guardar resultado de partido: ' . $e->getMessage());
            LivewireAlert::title('Error')
                ->text('Ocurrió un error al guardar el resultado. Intenta de nuevo.')
                ->error()
                ->show();
        }
    }

    public function saveAll(): void
    {
        $this->validate();

        try {
            DB::transaction(function () {
                foreach ($this->results as $matchId => $payload) {
                    $matchId = (int) $matchId;
                    $status = $payload['status'] ?? 'pending';

                    if ($status === 'finished' && ($payload['home_score'] === null || $payload['away_score'] === null)) {
                        throw new \RuntimeException('Hay partidos marcados como finalizados sin ambos marcadores.');
                    }

                    /** @var MatchGame $match */
                    $match = MatchGame::lockForUpdate()->findOrFail($matchId);

                    if ($status === 'pending') {
                        $match->home_score = null;
                        $match->away_score = null;
                    } else {
                        $match->home_score = $payload['home_score'];
                        $match->away_score = $payload['away_score'];
                    }

                    $match->status = $status;
                    $match->save();
                }
            });

            LivewireAlert::title('Éxito')
                ->text('Resultados guardados correctamente.')
                ->success()
                ->show();

            $this->loadEventMatches();
        } catch (Throwable $e) {
            Log::error('Error al guardar resultados de partidos: ' . $e->getMessage());
            LivewireAlert::title('Error')
                ->text('No se pudieron guardar los resultados. Verifica los marcadores e inténtalo de nuevo.')
                ->error()
                ->show();
        }
    }

    public function render()
    {
        return view('livewire.admin.matchresults');
    }
}

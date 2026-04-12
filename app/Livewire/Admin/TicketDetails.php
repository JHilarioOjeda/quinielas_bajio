<?php

namespace App\Livewire\Admin;

use App\Models\Prediction;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Throwable;

class TicketDetails extends Component
{
    public Ticket $ticket;

    /**
     * Selecciones por partido del ticket, usando el mismo esquema H/D/A
     * que en el llenado de la quiniela pública.
     *
     * [ticket_match_id => ['H' => bool, 'D' => bool, 'A' => bool]]
     */
    public array $picks = [];

    public int $totalCombinations = 0;
    public float $totalPrice = 0;

    public function mount(Ticket $ticket): void
    {
        $this->ticket = $ticket->load([
            'quinielaEvent',
            'ticketMatches.matchGame.homeTeam',
            'ticketMatches.matchGame.awayTeam',
            'ticketMatches.predictions',
        ]);

        $this->initializePicksFromPredictions();
        $this->recalculateTotals();
    }

    protected function initializePicksFromPredictions(): void
    {
        $this->picks = [];

        // Mapa inverso de 1/X/2 -> H/D/A
        $reverseMap = [
            '1' => 'H',
            'X' => 'D',
            '2' => 'A',
        ];

        foreach ($this->ticket->ticketMatches as $ticketMatch) {
            $this->picks[$ticketMatch->id] = [
                'H' => false,
                'D' => false,
                'A' => false,
            ];

            foreach ($ticketMatch->predictions as $prediction) {
                $code = $reverseMap[$prediction->selection] ?? null;

                if ($code !== null) {
                    $this->picks[$ticketMatch->id][$code] = true;
                }
            }
        }
    }

    public function updatedPicks(): void
    {
        $this->recalculateTotals();
    }

    protected function recalculateTotals(): void
    {
        $basePrice = $this->ticket->quinielaEvent?->price ?? 0;

        if ($basePrice <= 0 || empty($this->picks)) {
            $this->totalCombinations = 0;
            $this->totalPrice = 0;
            return;
        }

        $totalCombinations = 1;

        foreach ($this->picks as $selections) {
            if (! is_array($selections)) {
                $count = $selections ? 1 : 0;
            } else {
                $count = 0;
                foreach ($selections as $selected) {
                    if ($selected) {
                        $count++;
                    }
                }
            }

            if ($count === 0) {
                continue;
            }

            $totalCombinations *= $count;
        }

        if ($totalCombinations === 1) {
            $hasSelection = false;

            foreach ($this->picks as $selections) {
                if (is_array($selections)) {
                    foreach ($selections as $selected) {
                        if ($selected) {
                            $hasSelection = true;
                            break 2;
                        }
                    }
                } elseif ($selections) {
                    $hasSelection = true;
                    break;
                }
            }

            if (! $hasSelection) {
                $this->totalCombinations = 0;
                $this->totalPrice = 0;
                return;
            }
        }

        $this->totalCombinations = $totalCombinations;
        $this->totalPrice = $totalCombinations * $basePrice;
    }

    public function savePredictions(): void
    {
        // Validar que todos los partidos tengan al menos una selección
        $missingMatches = [];

        foreach ($this->ticket->ticketMatches as $ticketMatch) {
            $selections = $this->picks[$ticketMatch->id] ?? [];

            $count = 0;

            if (is_array($selections)) {
                foreach ($selections as $selected) {
                    if ($selected) {
                        $count++;
                    }
                }
            } elseif ($selections) {
                $count = 1;
            }

            if ($count === 0) {
                $missingMatches[] = $ticketMatch->id;
            }
        }

        if (! empty($missingMatches)) {
            LivewireAlert::title('Atención')
                ->text('Debes marcar al menos una opción en todos los partidos de la quiniela para este ticket.')
                ->warning()
                ->show();

            return;
        }

        $this->recalculateTotals();

        if ($this->totalCombinations <= 0 || $this->totalPrice <= 0) {
            LivewireAlert::title('Atención')
                ->text('La configuración actual de jugadas no es válida. Revisa las selecciones antes de guardar.')
                ->warning()
                ->show();

            return;
        }

        try {
            DB::beginTransaction();

            // Mapear H/D/A a 1/X/2 como en el llenado original
            $map = [
                'H' => '1',
                'D' => 'X',
                'A' => '2',
            ];

            foreach ($this->ticket->ticketMatches as $ticketMatch) {
                $selections = $this->picks[$ticketMatch->id] ?? [];

                // Borrar pronósticos anteriores
                $ticketMatch->predictions()->delete();

                foreach ($map as $key => $value) {
                    $selected = is_array($selections)
                        ? ($selections[$key] ?? false)
                        : ($selections === $key);

                    if ($selected) {
                        Prediction::create([
                            'ticket_match_id' => $ticketMatch->id,
                            'selection' => $value,
                        ]);
                    }
                }
            }

            // Actualizar importe del ticket según las nuevas jugadas
            $this->ticket->amount_paid = $this->totalPrice;
            $this->ticket->save();

            DB::commit();

            // Refrescar relaciones
            $this->ticket->load([
                'ticketMatches.matchGame.homeTeam',
                'ticketMatches.matchGame.awayTeam',
                'ticketMatches.predictions',
            ]);

            LivewireAlert::title('Éxito')
                ->text('Los pronósticos del ticket se actualizaron correctamente.')
                ->success()
                ->show();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Error al actualizar pronósticos del ticket: ' . $e->getMessage());

            LivewireAlert::title('Error')
                ->text('Ocurrió un error al actualizar los pronósticos. Inténtalo de nuevo.')
                ->error()
                ->show();
        }
    }

    public function render()
    {
        return view('livewire.admin.ticket-details', [
            'ticket' => $this->ticket,
        ]);
    }
}

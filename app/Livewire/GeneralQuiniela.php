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
use App\Models\Ticket;
use App\Models\TicketMatch;
use App\Models\Prediction;
use Illuminate\Support\Str;
use Carbon\Carbon;

class GeneralQuiniela extends Component
{
    /**
     * Selección por partido:
     * - Simples: 1 selección (H, D o A)
     * - Dobles: 2 selecciones (por ejemplo H y D)
     * - Triples: 3 selecciones (H, D y A)
     *
     * Estructura del arreglo:
     * [event_match_id => ['H' => bool, 'D' => bool, 'A' => bool]]
     */
    public array $picks = [];

    /** Nombre del jugador que llena la quiniela */
    public ?string $player_name = null;

    /** Datos del último ticket guardado para retroalimentación al usuario */
    public ?string $last_ticket_folio = null;
    public ?float $last_ticket_total = null;

    /** Controla la visibilidad del modal de reglamento */
    public bool $showRulesModal = false;

    public $whatsNumber, $whatsText;

    public function mount()
    {
        $this->whatsNumber = config('services.whatsapp.number');
        $this->whatsText = urlencode(' ');
    }

    public function render(){
        $quiniela = QuinielaEvent::with([
            'eventMatches.matchGame.homeTeam',
            'eventMatches.matchGame.awayTeam',
        ])->first();

        [$totalCombinations, $totalPrice] = $this->calculateTotals($quiniela);

        return view('livewire.general-quiniela', [
            'quiniela' => $quiniela,
            'totalCombinations' => $totalCombinations,
            'totalPrice' => $totalPrice,
        ]);
    }

    /**
     * Calcula el número de combinaciones (jugadas) y el total a pagar
     * en función de los dobles y triples seleccionados.
     */
    protected function calculateTotals(?QuinielaEvent $quiniela): array
    {
        $basePrice = $quiniela?->price ?? 0;

        if ($basePrice <= 0 || empty($this->picks)) {
            return [0, 0];
        }

        $totalCombinations = 1;

        foreach ($this->picks as $matchId => $selections) {
            // Compatibilidad: si viniera como string único (H/D/A)
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
            // Si solo hay un partido marcado con una selección, se considera 1 combinación,
            // pero si realmente no hay selecciones útiles, devolver 0.
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
                return [0, 0];
            }
        }

        $totalPrice = $totalCombinations * $basePrice;

        return [$totalCombinations, $totalPrice];
    }

    public function toggleRulesModal(): void
    {
        $this->showRulesModal = ! $this->showRulesModal;
    }

    /**
     * Guarda un ticket de quiniela validando que todos los partidos estén llenos.
     */
    public function saveTicket(): void
    {
        $quiniela = QuinielaEvent::with('eventMatches')->first();

        if (! $quiniela) {
            LivewireAlert::title('Error')
                ->text('No hay una quiniela activa para jugar.')
                ->error()
                ->show();
            return;
        }

        // Validar nombre del jugador
        $this->validate([
            'player_name' => 'required|string|max:100',
        ], [], [
            'player_name' => 'nombre del jugador',
        ]);

        // Validar que todos los partidos tengan al menos una selección
        $eventMatches = $quiniela->eventMatches;

        if ($eventMatches->isEmpty()) {
            LivewireAlert::title('Error')
                ->text('La quiniela no tiene partidos configurados.')
                ->error()
                ->show();
            return;
        }

        $missingMatches = [];

        foreach ($eventMatches as $eventMatch) {
            $selections = $this->picks[$eventMatch->id] ?? [];

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
                $missingMatches[] = $eventMatch->id;
            }
        }

        if (! empty($missingMatches)) {
            LivewireAlert::title('Atención')
                ->text('Debes marcar al menos una opción en todos los partidos de la quiniela (incluyendo el suplente si existe).')
                ->warning()
                ->show();
            return;
        }

        // Calcular total de jugadas e importe
        [$totalCombinations, $totalPrice] = $this->calculateTotals($quiniela);

        if ($totalCombinations <= 0 || $totalPrice <= 0) {
            LivewireAlert::title('Atención')
                ->text('Debes configurar al menos una jugada válida para poder guardar la quiniela.')
                ->warning()
                ->show();
            return;
        }

        try {
            DB::beginTransaction();

            $ticket = Ticket::create([
                'quiniela_event_id' => $quiniela->id,
                'folio' => $this->generateFolio($quiniela),
                'player_name' => $this->player_name,
                'amount_paid' => $totalPrice,
                'payment_status' => 'pendiente',
            ]);

            foreach ($eventMatches as $eventMatch) {
                $selections = $this->picks[$eventMatch->id] ?? [];

                $ticketMatch = TicketMatch::create([
                    'ticket_id' => $ticket->id,
                    'match_id' => $eventMatch->match_id,
                ]);

                // Mapear H/D/A a 1/X/2 como se documenta en la migración
                $map = [
                    'H' => '1',
                    'D' => 'X',
                    'A' => '2',
                ];

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

            DB::commit();

            // Guardar información del ticket para mostrarla al usuario
            $this->last_ticket_folio = $ticket->folio;
            $this->last_ticket_total = $totalPrice;

            // Limpiar selección y nombre después de guardar
            $this->picks = [];
            //$this->player_name = null;

            LivewireAlert::title('Éxito')
                ->text('Tu quiniela se guardó correctamente. Folio: ' . $ticket->folio)
                ->success()
                ->show();

            // Enviar evento al frontend para hacer scroll al resumen del ticket
            $this->dispatch('scroll-to-ticket');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Error al guardar ticket de quiniela: ' . $e->getMessage());

            LivewireAlert::title('Error')
                ->text('Ocurrió un error al guardar la quiniela. Inténtalo de nuevo.')
                ->error()
                ->show();
        }
    }

    protected function generateFolio(QuinielaEvent $quiniela): string
    {
        return 'Q' . $quiniela->id . '-' . strtoupper(Str::random(6));
    }
}

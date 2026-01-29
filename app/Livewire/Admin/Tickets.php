<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ticket;
use App\Models\QuinielaEvent;

class Tickets extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $quinielaId = null;
    public array $expandedTickets = [];

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'quinielaId' => 'nullable|integer|exists:quiniela_events,id',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedQuinielaId()
    {
        $this->resetPage();
    }

    public function toggleDetails(int $ticketId): void
    {
        if (in_array($ticketId, $this->expandedTickets, true)) {
            $this->expandedTickets = array_values(array_diff($this->expandedTickets, [$ticketId]));
        } else {
            $this->expandedTickets[] = $ticketId;
        }
    }

    public function setPaymentStatus(int $ticketId, string $status): void
    {
        if (! in_array($status, ['pendiente', 'pagado'], true)) {
            return;
        }

        $ticket = Ticket::find($ticketId);

        if (! $ticket) {
            return;
        }

        $ticket->payment_status = $status;
        $ticket->save();
    }

    public function togglePaymentStatus(int $ticketId): void
    {
        $ticket = Ticket::find($ticketId);

        if (! $ticket) {
            return;
        }

        $ticket->payment_status = $ticket->payment_status === 'pagado' ? 'pendiente' : 'pagado';
        $ticket->save();
    }

    public function render()
    {
        $this->validate();

        $quiniela = QuinielaEvent::first();

        $ticketsQuery = Ticket::query()
            ->where('active', true)
            ->when($this->quinielaId, function ($query) {
                $query->where('quiniela_event_id', $this->quinielaId);
            }, function ($query) use ($quiniela) {
                if ($quiniela) {
                    $query->where('quiniela_event_id', $quiniela->id);
                }
            })
            ->when($this->search, function ($query) {
                $search = "%{$this->search}%";
                $query->where(function ($q) use ($search) {
                    $q->where('player_name', 'like', $search)
                      ->orWhere('folio', 'like', $search);
                });
            })
            ->orderByDesc('created_at');

        $tickets = $ticketsQuery->paginate(50);

        return view('livewire.admin.tickets', [
            'tickets' => $tickets,
            'quiniela' => $quiniela,
        ]);
    }
}

<?php

namespace App\Livewire\Admin;

use App\Models\Ticket;
use Livewire\Component;

class TicketDetails extends Component
{
    public Ticket $ticket;

    public function mount(Ticket $ticket): void
    {
        $this->ticket = $ticket->load([
            'ticketMatches.matchGame.homeTeam',
            'ticketMatches.matchGame.awayTeam',
            'ticketMatches.predictions',
        ]);
    }

    public function render()
    {
        return view('livewire.admin.ticket-details', [
            'ticket' => $this->ticket,
        ]);
    }
}

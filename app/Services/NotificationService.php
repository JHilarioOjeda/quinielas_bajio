<?php

namespace App\Services;

use App\Enums\NotificationType;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\AppNotification;

class NotificationService
{
    /** Notifica a cada administrador dentro de la transacción del boleto. */
    public function ticketCreated(Ticket $ticket): void
    {
        User::query()->where('role', 'admin')->each(function (User $user) use ($ticket): void {
            $user->notify(new AppNotification(
                NotificationType::TicketCreated,
                "{$ticket->player_name} registró el boleto {$ticket->folio}.",
                ['ticket_id' => $ticket->id, 'quiniela_event_id' => $ticket->quiniela_event_id],
            ));
        });
    }
}

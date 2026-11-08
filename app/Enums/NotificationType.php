<?php

namespace App\Enums;

enum NotificationType: string
{
    case TicketCreated = 'ticket_created';

    public function label(): string
    {
        return match ($this) {
            self::TicketCreated => 'Nuevo boleto',
        };
    }

    /** La ruta se decide en el servidor, nunca desde datos recibidos del navegador. */
    public function routeName(): string
    {
        return match ($this) {
            self::TicketCreated => 'admin-tickets',
        };
    }
}

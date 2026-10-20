<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    public const PAYMENT_PENDING = 'pendiente';
    public const PAYMENT_PAID = 'pagado';

    public const PAYMENT_STATUSES = [
        self::PAYMENT_PENDING,
        self::PAYMENT_PAID,
    ];

    protected $fillable = [
        'quiniela_event_id',
        'folio',
        'player_name',
        'phone_number',
        'amount_paid',
        'payment_status',
        'active',
    ];

    public function quinielaEvent()
    {
        return $this->belongsTo(QuinielaEvent::class);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === self::PAYMENT_PAID;
    }

    public function ticketMatches()
    {
        return $this->hasMany(TicketMatch::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiniela_event_id',
        'folio',
        'player_name',
        'amount_paid',
        'payment_status',
        'active',
    ];

    public function quinielaEvent()
    {
        return $this->belongsTo(QuinielaEvent::class);
    }

    public function ticketMatches()
    {
        return $this->hasMany(TicketMatch::class);
    }
}

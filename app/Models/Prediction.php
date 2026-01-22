<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prediction extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_match_id',
        'selection',
    ];

    public function ticketMatch()
    {
        return $this->belongsTo(TicketMatch::class);
    }
}

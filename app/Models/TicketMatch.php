<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'match_id',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function matchGame()
    {
        return $this->belongsTo(MatchGame::class, 'match_id');
    }

    public function predictions()
    {
        return $this->hasMany(Prediction::class);
    }
}

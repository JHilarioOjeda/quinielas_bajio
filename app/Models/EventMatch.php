<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiniela_event_id',
        'match_id',
        'is_substitute',
    ];

    public function quinielaEvent()
    {
        return $this->belongsTo(QuinielaEvent::class);
    }

    public function matchGame()
    {
        return $this->belongsTo(MatchGame::class, 'match_id');
    }
}

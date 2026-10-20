<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuinielaEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'sales_start',
        'sales_end',
        'play_start',
        'play_end',
        'status',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function eventMatches()
    {
        return $this->hasMany(EventMatch::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * La jornada que se esta configurando o jugando actualmente. Una vez
     * finalizada (status=finished) deja de ser "la actual": sus resultados
     * y tickets se conservan como historial y la siguiente jornada vive en
     * un registro nuevo.
     */
    public function scopeCurrent($query)
    {
        return $query->where('status', '!=', 'finished')->latest('id');
    }
}

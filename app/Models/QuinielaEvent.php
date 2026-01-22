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
}

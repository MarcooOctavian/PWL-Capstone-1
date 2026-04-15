<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypeTicket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id',
        'name',
        'price',
        'stock',
        'max_purchase',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class)->withTrashed();
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}

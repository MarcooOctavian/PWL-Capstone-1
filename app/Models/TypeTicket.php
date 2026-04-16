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
        'schedule_id',
        'name',
        'price',
        'stock',
        'max_purchase',
    ];

    /**
     * Get the event that owns the type ticket.
     */
    public function event()
    {
        return $this->belongsTo(Event::class)->withTrashed();
    }

    /**
     * Get the schedule that owns the type ticket.
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class)->withTrashed();
    }

    /**
     * Get the tickets associated with the type ticket.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaitingList extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'event_id',
        'ticket_type_id',
        'status',
    ];

    /**
     * Get the user that owns the waiting list.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the event that owns the waiting list.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the ticket type that owns the waiting list.
     */
    public function ticketType()
    {
        return $this->belongsTo(TypeTicket::class, 'ticket_type_id');
    }
}

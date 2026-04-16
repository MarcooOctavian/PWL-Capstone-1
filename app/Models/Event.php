<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organizer_id',
        'category_id',
        'location_id',
        'title',
        'description',
        'banner_url',
        'date',
        'status',
    ];

    /**
     * Get the organizer that owns the event.
     */
    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id')->withTrashed();
    }

    /**
     * Get the category associated with the event.
     */
    public function category()
    {
        return $this->belongsTo(Category::class)->withTrashed();
    }

    /**
     * Get the location for the event.
     */
    public function location()
    {
        return $this->belongsTo(Location::class)->withTrashed();
    }

    /**
     * Get the schedules for the event.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Get the ticket types associated with the event.
     */
    public function typeTickets()
    {
        return $this->hasMany(TypeTicket::class);
    }

    /**
     * Relasi Has Many Through ke tabel tickets
     * Event memiliki banyak Ticket melalui TypeTicket
     */
    public function tickets()
    {
        return $this->hasManyThrough(
            \App\Models\Ticket::class,
            \App\Models\TypeTicket::class
        );
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'event_id',
        'location_id',
        'start_time',
        'end_time',
    ];

    /**
     * Get the event that owns the schedule.
     */
    public function event()
    {
        return $this->belongsTo(Event::class)->withTrashed();
    }

    /**
     * Get the location that owns the schedule.
     */
    public function location()
    {
        return $this->belongsTo(Location::class)->withTrashed();
    }
}

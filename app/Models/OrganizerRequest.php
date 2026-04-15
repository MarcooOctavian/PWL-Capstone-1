<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizerRequest extends Model
{
    protected $fillable = [
        'user_id',
        'organization_name',
        'reason',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'type_ticket_id',
        'qr_code',
        'status',
        'seat_number',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function typeTicket()
    {
        return $this->belongsTo(TypeTicket::class)->withTrashed();
    }
}

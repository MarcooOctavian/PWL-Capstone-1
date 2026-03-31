<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function create()
    {
        $typeTickets = \App\Models\TypeTicket::all();
        return view('user.checkout', compact('typeTickets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type_ticket_id' => 'required|exists:type_tickets,id',
            'qty' => 'required|integer|min:1',
        ]);

        $typeTicket = \App\Models\TypeTicket::findOrFail($request->type_ticket_id);
        
        // Calculate total
        $total_amount = $typeTicket->price * $request->qty;

        // Ensure user is logged in, or use a default user for testing if no auth setup yet
        $userId = \Illuminate\Support\Facades\Auth::id() ?? 1; // Fallback to user 1 if not logged in

        // Create Transaction
        $transaction = \App\Models\Transaction::create([
            'user_id' => $userId,
            'total_amount' => $total_amount,
            'payment_status' => 'paid', // Simulate success payment
            'transaction_date' => now(),
        ]);

        // Generate Tickets
        $firstTicketId = null;
        for ($i = 0; $i < $request->qty; $i++) {
            $ticketCode = 'TKT-' . strtoupper(\Illuminate\Support\Str::random(8));
            $ticket = \App\Models\Ticket::create([
                'transaction_id' => $transaction->id,
                'type_ticket_id' => $typeTicket->id,
                'qr_code' => $ticketCode,
                'status' => 'valid',
                'seat_number' => null,
            ]);
            
            if ($i === 0) $firstTicketId = $ticket->id;
        }

        // Redirect to E-Ticket page of the first ticket generated
        return redirect()->route('ticket.show', $firstTicketId)->with('success', 'Pembayaran Berhasil! Ini tiket Anda.');
    }
}

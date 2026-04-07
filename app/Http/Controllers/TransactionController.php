<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\TicketGeneratedMail; // Pastikan ini di-import

class TransactionController extends Controller
{
    public function create()
    {
        $typeTickets = \App\Models\TypeTicket::all();
        $schedules = \App\Models\Schedule::all();
        return view('user.checkout', compact('typeTickets', 'schedules'));
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

        // Ensure user is logged in
        $userId = Auth::id() ?? 1;

        // 1. Create Transaction
        $transaction = Transaction::create([
            'user_id' => $userId,
            'total_amount' => $total_amount,
            'payment_status' => 'paid',
            'transaction_date' => now(),
        ]);

        // 2. Generate Tickets
        $firstTicketId = null;
        for ($i = 0; $i < $request->qty; $i++) {
            $ticketCode = 'TKT-' . strtoupper(Str::random(8));
            $ticket = Ticket::create([
                'transaction_id' => $transaction->id,
                'type_ticket_id' => $typeTicket->id,
                'qr_code' => $ticketCode,
                'status' => 'valid',
                'seat_number' => null,
            ]);

            if ($i === 0) $firstTicketId = $ticket->id;
        }

        // 3. EMAIL E-TICKET
        if ($firstTicketId && Auth::check()) {
            $ticketData = Ticket::with(['transaction.user', 'typeTicket.event.location'])->find($firstTicketId);

            if ($ticketData) {
                Mail::to(Auth::user()->email)->send(new TicketGeneratedMail($ticketData));
            }
        }

        // 4. Redirect to E-Ticket page
        return redirect()->route('ticket.show', $firstTicketId)->with('success', 'Pembayaran Berhasil! E-Ticket telah dikirim ke email Anda.');
    }
}

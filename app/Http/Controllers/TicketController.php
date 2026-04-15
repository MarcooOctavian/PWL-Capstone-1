<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show E-Ticket (display all tickets in one transaction)
     */
    public function show($id)
    {
        $referenceTicket = Ticket::findOrFail($id);
        $allTickets = Ticket::with(['transaction.user', 'typeTicket.event'])
            ->where('transaction_id', $referenceTicket->transaction_id)
            ->get();
        return view('user.e-ticket', compact('allTickets'));
    }

    // Cancel ticket
    public function cancel($id)
    {
        $ticket = Ticket::findOrFail($id);
        $typeTicket = $ticket->typeTicket;

        // 1. Add stock back
        $typeTicket->increment('stock');

        // 2. Delete ticket
        $ticket->delete();

        // 3. Notification
        // Find the first person in the waiting list who is waiting for this ticket type
        $firstInLine = \App\Models\WaitingList::where('type_ticket_id', $typeTicket->id)
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->first();

        if ($firstInLine) {
            //
        }

        return back()->with('success', 'Tiket berhasil dibatalkan dan stok telah dikembalikan.');
    }

    // Scan ticket
    public function scanTicket($qr_code)
    {
        $ticket = Ticket::with(['transaction.user', 'typeTicket.event'])
            ->where('qr_code', $qr_code)
            ->firstOrFail();

        return view('user.scan-result', compact('ticket'));
    }

    // Process scan
    public function processScan(Request $request, $qr_code)
    {
        $ticket = Ticket::where('qr_code', $qr_code)->firstOrFail();
        
        if ($ticket->status !== 'valid') {
            return back()->with('error', 'Tiket ini tidak valid atau sudah digunakan.');
        }

        $ticket->update(['status' => 'used']);

        return back()->with('success', 'Check-In berhasil! Tiket telah digunakan.');
    }
}

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
     * Show E-Ticket (menampilkan semua tiket dalam 1 kali transaksi)
     */
    public function show($id)
    {
        $referenceTicket = Ticket::findOrFail($id);
        $allTickets = Ticket::with(['transaction.user', 'typeTicket.event'])
            ->where('transaction_id', $referenceTicket->transaction_id)
            ->get();
        return view('user.e-ticket', compact('allTickets'));
    }

    public function cancel($id)
    {
        $ticket = Ticket::findOrFail($id);
        $typeTicket = $ticket->typeTicket;

        // 1. Tambahkan stok kembali
        $typeTicket->increment('stock');

        // 2. Hapus tiket
        $ticket->delete();

        // 3. Notifikasi
        $firstInLine = \App\Models\WaitingList::where('type_ticket_id', $typeTicket->id)
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->first();

        if ($firstInLine) {
        }

        return back()->with('success', 'Tiket berhasil dibatalkan dan stok telah dikembalikan.');
    }
}

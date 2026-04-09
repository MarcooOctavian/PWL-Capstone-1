<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\TicketGeneratedMail;

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
        // 1. Validasi Input Dasar
        $request->validate([
            'type_ticket_id' => 'required|exists:type_tickets,id',
            'qty' => 'required|integer|min:1',
            'email' => 'required|email',
        ], [
            'qty.min' => 'Minimal pembelian adalah 1 tiket.'
        ]);

        $typeTicket = \App\Models\TypeTicket::findOrFail($request->type_ticket_id);

        // --- ERROR HANDLING ---

        // Kondisi 1: Cek Batas Maksimal Pembelian per Transaksi (max_purchase)
        if ($request->qty > $typeTicket->max_purchase) {
            return back()->withInput()->withErrors([
                'qty' => 'Mohon maaf, batas maksimal pembelian untuk tiket ' . $typeTicket->name . ' adalah ' . $typeTicket->max_purchase . ' tiket per transaksi.'
            ]);
        }

        // Kondisi 2: Cek Sisa Stok Total di Database (stock)
        // Jika tiket benar-benar habis (0)
        if ($typeTicket->stock <= 0) {
            return back()->with('error', 'Maaf, tiket jenis ini sudah habis total! Silakan mendaftar ke Waiting List untuk mendapat antrean jika ada pembeli yang batal.');
        }

        // Kondisi 3: Cek jika sisa stok lebih kecil dari yang mau dibeli
        if ($request->qty > $typeTicket->stock) {
            return back()->withInput()->withErrors([
                'qty' => 'Mohon maaf, transaksi ditolak. Anda mencoba membeli ' . $request->qty . ' tiket, namun sisa stok saat ini hanya ' . $typeTicket->stock . ' tiket.'
            ]);
        }

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

        // 3. PENGURANGAN STOK
        $typeTicket->decrement('stock', $request->qty);

        // 4. EMAIL E-TICKET
        if ($firstTicketId) {
            $ticketData = Ticket::with(['typeTicket.event.location', 'transaction.user'])->find($firstTicketId);

            if ($ticketData) {
                try {
                    Mail::to($request->email)->send(new TicketGeneratedMail($ticketData));
                } catch (\Exception $e) {
                    \Log::error("Gagal kirim email: " . $e->getMessage());
                }
            }
        }

        // 5. Redirect to E-Ticket page
        return redirect()->route('ticket.show', $firstTicketId)->with('success', 'Pembayaran Berhasil! E-Ticket telah dikirim ke email Anda.');
    }
}

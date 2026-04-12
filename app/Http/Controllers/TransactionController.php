<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\TicketGeneratedMail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;


class TransactionController extends Controller
{
    public function create(Request $request)
    {
        $event = \App\Models\Event::findOrFail($request->event_id);
        $typeTickets = \App\Models\TypeTicket::where('event_id', $event->id)->get();
        $schedules = \App\models\Schedule::with('location')
            ->where('event_id', $event->id)
            ->get();
        return view('user.checkout', compact('event', 'typeTickets', 'schedules'));
    }

    // FUNGSI STORE
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'type_ticket_id' => 'required|exists:type_tickets,id',
            'qty' => 'required|integer|min:1',
            'name' => 'required|string',
            'email' => 'required|email',
            'payment_method' => 'required|string',
            'payment_credential' => 'required|string',
        ], [
            'qty.min' => 'Minimal pembelian adalah 1 tiket.'
        ]);

        $typeTicket = \App\Models\TypeTicket::findOrFail($request->type_ticket_id);

        // --- ERROR HANDLING LIMIT & STOK ---
        if ($request->qty > $typeTicket->max_purchase) {
            return back()->withInput()->withErrors([
                'qty' => 'Mohon maaf, batas maksimal pembelian untuk tiket ' . $typeTicket->name . ' adalah ' . $typeTicket->max_purchase . ' tiket per transaksi.'
            ]);
        }
        if ($typeTicket->stock <= 0) {
            return back()->withInput()->with('error', 'Maaf, tiket jenis ini sudah habis total! Silakan mendaftar ke Waiting List.');
        }
        if ($request->qty > $typeTicket->stock) {
            return back()->withInput()->withErrors([
                'qty' => 'Mohon maaf, transaksi ditolak. Anda mencoba membeli ' . $request->qty . ' tiket, namun sisa stok saat ini hanya ' . $typeTicket->stock . ' tiket.'
            ]);
        }

        // 2. Simpan Data ke Session
        $checkoutData = [
            'type_ticket_id' => $typeTicket->id,
            'ticket_name' => $typeTicket->name,
            'qty' => $request->qty,
            'total_amount' => $typeTicket->price * $request->qty,
            'name' => $request->name,
            'email' => $request->email,
            'payment_method' => $request->payment_method,
            'payment_credential' => $request->payment_credential,
        ];
        session()->put('checkout_data', $checkoutData);

        // 3. Lempar ke halaman Pembayaran
        return redirect()->route('checkout.payment');
    }

    // FUNGSI UNTUK MENAMPILKAN HALAMAN SIMULASI PEMBAYARAN
    public function payment()
    {
        $checkoutData = session('checkout_data');
        if (!$checkoutData) {
            return redirect()->route('checkout.create')->with('error', 'Sesi pembayaran telah habis. Silakan ulangi pesanan Anda.');
        }

        return view('user.payment', compact('checkoutData'));
    }

    // FUNGSI UNTUK MENGEKSEKUSI PEMBAYARAN (Masuk DB, Potong Stok, Kirim Email)
    public function processPayment(Request $request)
    {
        $checkoutData = session('checkout_data');
        if (!$checkoutData) {
            return redirect()->route('checkout.create')->with('error', 'Sesi pembayaran tidak valid.');
        }

        // Re-verify ticket
        $typeTicket = \App\Models\TypeTicket::findOrFail($checkoutData['type_ticket_id']);

        // 1. Create Transaction DB (Status Paid)
        $userId = Auth::id() ?? 1;
        $transaction = Transaction::create([
            'user_id' => $userId,
            'total_amount' => $checkoutData['total_amount'],
            'payment_status' => 'paid',
            'transaction_date' => now(),
        ]);

        // 2. Generate Tickets
        $firstTicketId = null;
        for ($i = 0; $i < $checkoutData['qty']; $i++) {
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

        // 3. Kurangi Stok
        $typeTicket->decrement('stock', $checkoutData['qty']);

        // 4. Kirim Email
        if ($transaction) {
            $allTickets = Ticket::with(['typeTicket.event.location', 'transaction.user'])
                ->where('transaction_id', $transaction->id)
                ->get();

            try {
                Mail::to($checkoutData['email'])->send(new TicketGeneratedMail($allTickets));
            } catch (\Exception $e) {
                \Log::error("Gagal kirim email: " . $e->getMessage());
            }
        }

        // 5. Bersihkan session
        session()->forget('checkout_data');

        return redirect()->route('ticket.show', $firstTicketId)->with('success', 'Pembayaran Berhasil Diverifikasi! E-Ticket telah dikirim ke email Anda.');
    }

    /**
     * EXPORT EXCEL
     */
    public function exportExcel()
    {
        return Excel::download(new TransactionsExport, 'Laporan_Detail_ETicket.xlsx');
    }
}

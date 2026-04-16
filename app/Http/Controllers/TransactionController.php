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
    /**
     * Create transaction
     */
    public function create(Request $request)
    {
        if (!$request->event_id) {
            return redirect()->route('events.public')
                ->with('error', 'Pilih event dulu');
        }
        $event = \App\Models\Event::findOrFail($request->event_id);
        $typeTickets = \App\Models\TypeTicket::where('event_id', $event->id)->get();
        $schedules = \App\models\Schedule::with('location')
            ->where('event_id', $event->id)
            ->get();
        return view('user.checkout', compact('event', 'typeTickets', 'schedules'));
    }

    /**
     * Store transaction
     */
    public function store(Request $request)
    {
        // input validation
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

        // --- ERROR HANDLING LIMIT & STOCK ---
        $userId = Auth::id() ?? 1;

        // Calculate total tickets already purchased or currently pending for this user
        $purchasedTicketsCount = \App\Models\Ticket::where('type_ticket_id', $typeTicket->id)
            ->whereHas('transaction', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->whereIn('payment_status', ['paid', 'pending']);
            })
            ->count();

        // Enforce user maximum purchase limit
        if (($purchasedTicketsCount + $request->qty) > $typeTicket->max_purchase) {
            return redirect()->route('checkout.create', [
                'event_id' => $typeTicket->event_id
            ])->withInput()->withErrors([
                'qty' => 'Batas maksimal pembelian (' . $typeTicket->max_purchase . '). Anda sudah memiliki/memesan ' . $purchasedTicketsCount . ' tiket jenis ini.'
            ]);
        }
        if ($typeTicket->stock <= 0) {
            return redirect()->route('checkout.create', [
                'event_id' => $typeTicket->event_id
            ])->withInput()->with('error', 'Maaf, tiket jenis ini sudah habis total! Silakan mendaftar ke Waiting List.');
        }
        if ($request->qty > $typeTicket->stock) {
            return redirect()->route('checkout.create', [
                'event_id' => $typeTicket->event_id
            ])->withInput()->withErrors([
                'qty' => 'Mohon maaf, transaksi ditolak. Anda mencoba membeli ' . $request->qty . ' tiket, namun sisa stok saat ini hanya ' . $typeTicket->stock . ' tiket.'
            ]);
        }

        // 2. Save Data to Session
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

        // Save Transaction Database (Pending)
        $userId = Auth::id() ?? 1;
        
        // Create initial placeholder transaction before payment
        $transaction = Transaction::create([
            'user_id' => $userId,
            'total_amount' => $checkoutData['total_amount'],
            'payment_status' => 'pending',
            'transaction_date' => now(),
        ]);
        
        // Generate individual tickets with unique QR codes for the requested quantity
        for ($i = 0; $i < $request->qty; $i++) {
            $ticketCode = 'TKT-' . strtoupper(Str::random(8));
            Ticket::create([
                'transaction_id' => $transaction->id,
                'type_ticket_id' => $typeTicket->id,
                'qr_code' => $ticketCode,
                'status' => 'unpaid',
            ]);
        }
        
        $typeTicket->decrement('stock', $request->qty);

        // Redirect to payment simulation page to complete the transaction
        return redirect()->route('checkout.payment', $transaction->id);
    }

    /**
     * Display payment simulation page
     */
    public function payment($id)
    {
        $transaction = Transaction::findOrFail($id);
        $checkoutData = session('checkout_data');
        
        if (!$checkoutData || $transaction->payment_status !== 'pending') {
            return redirect()->route('checkout.create')->with('error', 'Sesi pembayaran telah habis atau tidak valid. Silakan ulangi pesanan Anda.');
        }

        return view('user.payment', compact('checkoutData', 'transaction'));
    }

    /**
     * Process payment (Update Status, Send Email)
     */
    public function processPayment(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        
        if ($transaction->payment_status !== 'pending') {
            return redirect()->route('home')->with('error', 'Transaksi sudah diproses sebelumnya.');
        }

        // 1. Update Transaction & Tickets
        // Mark transaction as paid and activate associated tickets
        $transaction->update(['payment_status' => 'paid']);
        $transaction->tickets()->update(['status' => 'valid']);

        $checkoutData = session('checkout_data');

        // 2. Send Email
        if ($checkoutData) {
            $allTickets = Ticket::with(['typeTicket.event.location', 'transaction.user'])
                ->where('transaction_id', $transaction->id)
                ->get();

            try {
                Mail::to($checkoutData['email'])->send(new TicketGeneratedMail($allTickets));
            } catch (\Exception $e) {
                \Log::error("Gagal kirim email: " . $e->getMessage());
            }
        }

        // 3. Clear session
        session()->forget('checkout_data');
        $firstTicket = $transaction->tickets()->first();

        return redirect()->route('ticket.show', $firstTicket->id)->with('success', 'Pembayaran Berhasil Diverifikasi! E-Ticket telah dikirim ke email Anda.');
    }

    /**
     * Function for time out or failed payment
     */
    public function failPayment($id)
    {
        $transaction = Transaction::findOrFail($id);
        
        if ($transaction->payment_status === 'pending') {
            $transaction->update(['payment_status' => 'failed']);
            $transaction->tickets()->update(['status' => 'cancelled']);
            
            // Revert ticket stock back to availability pool
            $firstTicket = $transaction->tickets()->first();
            if ($firstTicket) {
                $typeTicket = $firstTicket->typeTicket;
                $typeTicket->increment('stock', $transaction->tickets()->count());
            }
        }
        
        session()->forget('checkout_data');

        return redirect()->route('events.public')->with('error', 'Waktu pembayaran telah habis atau dibatalkan. Transaksi dinyatakan GAGAL.');
    }

    /**
     * EXPORT EXCEL
     */
    public function exportExcel()
    {
        return Excel::download(new TransactionsExport, 'Laporan_Detail_ETicket.xlsx');
    }
}

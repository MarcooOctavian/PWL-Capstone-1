<?php

namespace App\Http\Controllers;

use App\Models\WaitingList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class WaitingListController extends Controller
{
    /**
     * Display queue list Admin / Organizer
     */
    public function index()
    {
        $waitingLists = WaitingList::with(['user', 'event', 'ticketType'])->latest()->paginate(10);
        return view('admin.waiting_list.index', compact('waitingLists'));
    }

    /**
     * Saving user to queue list
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'type_ticket_id' => 'required|exists:type_tickets,id',
        ]);

        // Resolve ticket type column name dynamically to handle database schema variations
        $columnName = Schema::hasColumn('waiting_lists', 'type_ticket_id') ? 'type_ticket_id' : 'ticket_type_id';

        $alreadyWaiting = WaitingList::where('user_id', Auth::id())
            ->where($columnName, $request->type_ticket_id)
            ->whereIn('status', ['waiting', 'notified'])
            ->exists();

        if ($alreadyWaiting) {
            return back()->with('error', 'Anda sudah berada di dalam daftar antrean untuk tiket ini.');
        }

        WaitingList::create([
            'user_id' => Auth::id(),
            'event_id' => $request->event_id,
            $columnName => $request->type_ticket_id,
            'status' => 'waiting',
        ]);

        return back()->with('success', 'Berhasil masuk ke daftar antrean. Kami akan mengirimkan email jika kuota tersedia.');
    }

    /**
     * Alter queue status
     */
    public function update(Request $request, WaitingList $waitingList)
    {
        $request->validate([
            'status' => 'required|in:waiting,notified,purchased,canceled'
        ]);

        $waitingList->update(['status' => $request->status]);

        return back()->with('success', 'Status antrean berhasil diperbarui.');
    }

    /**
     * User Join Waiting List Function
     */
    public function join(Request $request)
    {
        // 1. Input Validation
        $request->validate([
            'type_ticket_id' => 'required|exists:type_tickets,id',
            'name' => 'required|string',
            'email' => 'required|email'
        ]);

        $typeTicket = \App\Models\TypeTicket::findOrFail($request->type_ticket_id);

        // 2. Prepare basic data
        $data = [
            'user_id' => auth()->id(),
            'name' => $request->name,
            'email' => $request->email,
            'event_id' => $typeTicket->event_id ?? 2,
            'status' => 'waiting'
        ];

        // 3. Smart Logic
        // Adjust array key based on actual database column name
        if (Schema::hasColumn('waiting_lists', 'type_ticket_id')) {
            $data['type_ticket_id'] = $typeTicket->id;
        } else {
            $data['ticket_type_id'] = $typeTicket->id;
        }

        // 4. Execute Save
        \App\Models\WaitingList::create($data);

        // 5. Return with success
        return redirect()->route('checkout.create')->with('success', 'Berhasil! Anda telah dimasukkan ke dalam antrean Waiting List. Kami akan menghubungi Anda via email jika ada tiket yang tersedia.');
    }

    /**
     * User Respond to Ticket Offer Function (ACCEPT/DECLINE)
     */
    public function respond(Request $request, $id)
    {
        $waitingList = WaitingList::findOrFail($id);

        // Dynamically locate the correct column holding the ticket relationship
        $columnName = Schema::hasColumn('waiting_lists', 'type_ticket_id') ? 'type_ticket_id' : 'ticket_type_id';
        $ticketId = $waitingList->$columnName;

        $typeTicket = \App\Models\TypeTicket::findOrFail($ticketId);

        // JIKA USER KLIK ACCEPT (AMBIL TIKET)
        if ($request->action == 'accept') {
            $waitingList->update(['status' => 'purchased']);

            // Mengembalikan 1 stok yang tadi di-booking, lalu lempar ke form checkout
            $typeTicket->increment('stock', 1);

            // Beri token khusus (session) agar tidak kena validasi stok habis
            session()->put('bypass_stock_for_wl', true);

            return redirect()->route('checkout.create', ['event_id' => $waitingList->event_id])
                ->with('success', 'Kuota berhasil diamankan! Silakan isi data diri dan selesaikan pembayaran untuk tiket ' . $typeTicket->name . '.');
        }

        // IF USER CLICK DECLINE (DECLINE TICKET)
        if ($request->action == 'decline') {
            // 1. Update status to canceled
            $waitingList->update(['status' => 'canceled']);

            // 2. Revert 1 stock that was booked
            $typeTicket->increment('stock', 1);

            // 3. Automatically search for replacement (Auto Oper to the next person)
            // Query the next users in line waiting for this specific ticket type
            $nextInLine = WaitingList::where($columnName, $typeTicket->id)
                ->where('status', 'waiting')
                ->orderBy('created_at', 'asc')
                ->first();

            if ($nextInLine) {
                // Beri notif ke orang selanjutnya, lalu booking lagi stoknya
                $nextInLine->update(['status' => 'notified']);
                $typeTicket->decrement('stock', 1);
            }

            return back()->with('success', 'Anda telah menolak tiket. Kesempatan otomatis diberikan ke antrean berikutnya.');
        }
        return back()->with('error', 'Aksi tidak valid atau tidak dikenali.');
    }
}

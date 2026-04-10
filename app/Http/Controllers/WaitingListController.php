<?php

namespace App\Http\Controllers;

use App\Models\WaitingList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $alreadyWaiting = WaitingList::where('user_id', Auth::id())
            ->where('type_ticket_id', $request->type_ticket_id)
            ->whereIn('status', ['waiting', 'notified'])
            ->exists();

        if ($alreadyWaiting) {
            return back()->with('error', 'Anda sudah berada di dalam daftar antrean untuk tiket ini.');
        }

        WaitingList::create([
            'user_id' => Auth::id(),
            'event_id' => $request->event_id,
            'type_ticket_id' => $request->type_ticket_id,
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

    // FUNGSI UNTUK USER MASUK ANTREAN WAITING LIST
    public function join(Request $request)
    {
        // 1. Validasi data
        $request->validate([
            'type_ticket_id' => 'required|exists:type_tickets,id',
            'name' => 'required|string',
            'email' => 'required|email'
        ]);

        $typeTicket = \App\Models\TypeTicket::findOrFail($request->type_ticket_id);

        // 2. Siapkan data dasar
        $data = [
            'user_id' => auth()->id(),
            'name' => $request->name,
            'email' => $request->email,
            'event_id' => $typeTicket->event_id ?? 2,
            'status' => 'waiting'
        ];

        // 3. LOGIKA PINTAR
        if (\Illuminate\Support\Facades\Schema::hasColumn('waiting_lists', 'type_ticket_id')) {
            $data['type_ticket_id'] = $typeTicket->id;
        } else {
            $data['ticket_type_id'] = $typeTicket->id;
        }

        // 4. Eksekusi Simpan
        \App\Models\WaitingList::create($data);

        // 5. Kembali dengan sukses
        return redirect()->route('checkout.create')->with('success', 'Berhasil! Anda telah dimasukkan ke dalam antrean Waiting List. Kami akan menghubungi Anda via email jika ada tiket yang tersedia.');
    }
}

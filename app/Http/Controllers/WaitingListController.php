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
}

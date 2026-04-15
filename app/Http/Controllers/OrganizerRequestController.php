<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrganizerRequest;
use App\Models\User;

class OrganizerRequestController extends Controller
{
    /**
     * Display all organizer requests
     */
    public function index()
    {
        $requests = OrganizerRequest::with('user')->latest()->get();
        return view('admin.organizer-requests', compact('requests'));
    }

    /**
     * Create organizer request
     */
    public function create()
    {
        $user = auth()->user();
        $pending = \App\Models\OrganizerRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();
        return view('user.organizer-request', compact('pending'));
    }

    /**
     * Store organizer request
     */
    public function store(Request $request)
    {
        $request->validate([
            'organization_name' => 'nullable|string|max:255',
            'reason' => 'required|string|min:10',
        ]);

        $user = auth()->user();

        $pending = \App\Models\OrganizerRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($pending) {
            return back()->with('error', 'Permintaan Anda masih diproses.');
        }

        \App\Models\OrganizerRequest::create([
            'user_id' => $user->id,
            'organization_name' => $request->organization_name,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('organizer.request')
            ->with('success', 'Permintaan berhasil dikirim!');
    }

    /**
     * Approve organizer request
     */
    public function approve($id)
    {
        $req = OrganizerRequest::findOrFail($id);

        $user = $req->user;
        $user->role = 2; // organizer
        $user->save();

        $req->status = 'approved';
        $req->save();
        return back()->with('success', 'User sekarang menjadi organizer.');
    }

    /**
     * Reject organizer request
     */
    public function reject($id)
    {
        $req = OrganizerRequest::findOrFail($id);
        $req->status = 'rejected';
        $req->save();
        return back()->with('success', 'Request ditolak.');
    }
}

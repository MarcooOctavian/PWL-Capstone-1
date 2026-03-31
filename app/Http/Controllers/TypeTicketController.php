<?php

namespace App\Http\Controllers;

use App\Models\TypeTicket;
use Illuminate\Http\Request;

class TypeTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ticketTypes = TypeTicket::with('event')->get();
        return view('admin.ticket_types.index', compact('ticketTypes'));
    }

    public function create()
    {
        $events = \App\Models\Event::all();
        return view('admin.ticket_types.create', compact('events'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:1',
            'max_purchase' => 'required|integer|min:1',
        ]);

        TypeTicket::create($data);
        return redirect()->route('ticket-types.index')->with('success', 'Ticket Type added successfully!');
    }

    public function show(TypeTicket $typeTicket)
    {
        // 
    }

    public function edit($id)
    {
        $ticketType = TypeTicket::findOrFail($id);
        $events = \App\Models\Event::all();
        return view('admin.ticket_types.edit', compact('ticketType', 'events'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:1',
            'max_purchase' => 'required|integer|min:1',
        ]);

        $ticketType = TypeTicket::findOrFail($id);
        $ticketType->update($data);

        return redirect()->route('ticket-types.index')->with('success', 'Ticket Type updated successfully!');
    }

    public function destroy($id)
    {
        TypeTicket::findOrFail($id)->delete();
        return back()->with('success', 'Ticket Type deleted successfully!');
    }
}

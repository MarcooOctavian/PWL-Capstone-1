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
        $events = \App\Models\Event::all();
        return view('admin.ticket_types.index', compact('events'));
    }

    public function create(Request $request)
    {
        $events = \App\Models\Event::all();
        $selectedEvent = $request->event_id;
        return view('admin.ticket_types.create', compact('events','selectedEvent'));
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
        return redirect()->route('ticket-types.manage', $request->event_id)
            ->with('success', 'Ticket created successfully');
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

        return redirect()->route('ticket-types.manage',$request->event_id)->with('success', 'Ticket Type updated successfully!');
    }

    public function destroy($id)
    {
        TypeTicket::findOrFail($id)->delete();
        return back()->with('success', 'Ticket Type deleted successfully!');
    }

    public function byEvent($id)
    {
        $event = \App\Models\Event::findOrFail($id);

        $ticketTypes = TypeTicket::with('event')
            ->where('event_id', $id)
            ->get();

        return view('admin.ticket_types.manage', compact('ticketTypes', 'event'));
    }
}

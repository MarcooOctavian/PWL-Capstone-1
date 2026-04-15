<?php

namespace App\Http\Controllers;

use App\Models\TypeTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class TypeTicketController extends Controller
{
    /**
     * Display all ticket types
     */
    public function index()
    {
        if (auth()->user()->role == 1) {
            $events = \App\Models\Event::all();
        } else {
            $events = \App\Models\Event::where('organizer_id', auth()->id())->get();
        }
        return view('admin.ticket_types.index', compact('events'));
    }

    /**
     * Create ticket type
     */
    public function create(Request $request)
    {
        if (auth()->user()->role == 1) {
            $events = \App\Models\Event::all();
        } else {
            $events = \App\Models\Event::where('organizer_id', auth()->id())->get();
        }
        $selectedEvent = $request->event_id;
        $eventObj = \App\Models\Event::findOrFail($selectedEvent);
        if (strtolower($eventObj->status) === 'completed') {
            return redirect()->route('ticket-types.manage', $selectedEvent)->with('error', 'Event completed. Cannot add tickets.');
        }
        $schedules = \App\Models\Schedule::where('event_id', $selectedEvent)->get();
        return view('admin.ticket_types.create', compact('events','selectedEvent', 'schedules'));
    }

    /**
     * Store ticket type
     */
    public function store(Request $request)
    {
        $event = \App\Models\Event::findOrFail($request->event_id);
        if (auth()->user()->role != 1 && $event->organizer_id != auth()->id()) {
            abort(403, 'Akses ditolak');
        }
        if (strtolower($event->status) === 'completed') {
            abort(403, 'Event is completed. Cannot modify tickets.');
        }

        $data = $request->validate([
            'event_id' => 'required|exists:events,id',
            'schedule_id' => 'required|exists:schedules,id',
            'name' => [
                'required',
                'string',
                'max:50',
                \Illuminate\Validation\Rule::unique('type_tickets')->where(function ($query) use ($request) {
                    return $query->where('schedule_id', $request->schedule_id);
                })
            ],
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

    /**
     * Edit ticket type
     */
    public function edit($id)
    {
        $ticketType = TypeTicket::findOrFail($id);
        if (auth()->user()->role != 1 && $ticketType->event->organizer_id != auth()->id()) {
            abort(403, 'Akses ditolak');
        }
        if (strtolower($ticketType->event->status) === 'completed') {
            return redirect()->route('ticket-types.manage', $ticketType->event_id)->with('error', 'Event completed. Cannot edit tickets.');
        }
        if (auth()->user()->role == 1) {
            $events = \App\Models\Event::all();
        } else {
            $events = \App\Models\Event::where('organizer_id', auth()->id())->get();
        }
        $schedules = \App\Models\Schedule::where('event_id', $ticketType->event_id)->get();
        return view('admin.ticket_types.edit', compact('ticketType', 'events', 'schedules'));
    }

    /**
     * Update ticket type
     */
    public function update(Request $request, $id)
    {
        $ticketType = TypeTicket::findOrFail($id);
        if (auth()->user()->role != 1 && $ticketType->event->organizer_id != auth()->id()) {
            abort(403, 'Akses ditolak');
        }

        $event = \App\Models\Event::findOrFail($request->event_id);
        if (auth()->user()->role != 1 && $event->organizer_id != auth()->id()) {
            abort(403, 'Akses ditolak');
        }
        if (strtolower($event->status) === 'completed') {
            abort(403, 'Event is completed. Cannot modify tickets.');
        }

        $data = $request->validate([
            'event_id' => 'required|exists:events,id',
            'schedule_id' => 'required|exists:schedules,id',
            'name' => [
                'required',
                'string',
                'max:50',
                \Illuminate\Validation\Rule::unique('type_tickets')->where(function ($query) use ($request) {
                    return $query->where('schedule_id', $request->schedule_id);
                })->ignore($id)
            ],
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:1',
            'max_purchase' => 'required|integer|min:1',
        ]);

        $oldStock = $ticketType->stock;

        $ticketType->update($data);

        // Automatically check if admin just added stock
        // Trigger waiting list notification if ticket stock was successfully replenished from zero
        if ($oldStock <= 0 && $ticketType->stock > 0) {

            $columnName = Schema::hasColumn('waiting_lists', 'type_ticket_id') ? 'type_ticket_id' : 'ticket_type_id';

            // Find oldest users waiting in line up to the amount of new stock added
            $pengantre = \App\Models\WaitingList::where($columnName, $ticketType->id)
                ->where('status', 'waiting')
                ->orderBy('created_at', 'asc')
                ->take($ticketType->stock)
                ->get();

            foreach ($pengantre as $orang) {
                // 1. Update status to notified
                $orang->update(['status' => 'notified']);

                // 2. 'Booking' stock so it's not bought by the general public
                $ticketType->decrement('stock', 1);
            }
        }

        return redirect()->route('ticket-types.manage',$request->event_id)->with('success', 'Ticket Type updated successfully!');
    }

    /**
     * Delete ticket type
     */
    public function destroy($id)
    {
        $ticketType = TypeTicket::findOrFail($id);
        if (auth()->user()->role != 1 && $ticketType->event->organizer_id != auth()->id()) {
            abort(403, 'Akses ditolak');
        }
        if (strtolower($ticketType->event->status) === 'completed') {
            abort(403, 'Event is completed. Cannot delete tickets.');
        }
        $ticketType->delete();
        return back()->with('success', 'Ticket Type deleted successfully!');
    }

    /**
     * Get ticket types by event
     */
    public function byEvent($id)
    {
        $event = \App\Models\Event::findOrFail($id);
        if (auth()->user()->role != 1 && $event->organizer_id != auth()->id()) {
            abort(403, 'Akses ditolak');
        }

        $ticketTypes = TypeTicket::with(['event', 'schedule'])
            ->where('event_id', $id)
            ->get();
        $schedules = \App\Models\Schedule::with('location')->where('event_id', $id)->get();

        return view('admin.ticket_types.manage', compact('ticketTypes', 'event', 'schedules'));
    }
}

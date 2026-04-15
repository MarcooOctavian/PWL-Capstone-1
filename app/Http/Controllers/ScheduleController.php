<?php
namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Event;
use App\Models\Location;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display all schedules
     */
    public function index()
    {
        if (auth()->user()->role == 1) {
            $schedules = Schedule::with(['event', 'location'])->get();
        } else {
            $schedules = Schedule::with(['event', 'location'])
                ->whereHas('event', function($q) {
                    $q->where('organizer_id', auth()->id());
                })->get();
        }
        return view('schedule.index', compact('schedules'));
    }

    /**
     * Create schedule
     */
    public function create()
    {
        if (auth()->user()->role == 1) {
            $events = Event::all();
        } else {
            $events = Event::where('organizer_id', auth()->id())->get();
        }
        $locations = Location::all();
        return view('schedule.create', compact('events', 'locations'));
    }

    /**
     * Store schedule
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'location_id' => 'required|exists:locations,id',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $event = Event::findOrFail($request->event_id);
        if (auth()->user()->role != 1 && $event->organizer_id != auth()->id()) {
            abort(403, 'Akses ditolak');
        }

        Schedule::create($request->all());
        return redirect()->route('schedules.index')->with('success', 'Schedule created successfully.');
    }

    /**
     * Edit schedule
     */
    public function edit(Schedule $schedule)
    {
        if (auth()->user()->role != 1 && $schedule->event->organizer_id != auth()->id()) {
            abort(403, 'Akses ditolak');
        }
        if (auth()->user()->role == 1) {
            $events = Event::all();
        } else {
            $events = Event::where('organizer_id', auth()->id())->get();
        }
        $locations = Location::all();
        return view('schedule.edit', compact('schedule', 'events', 'locations'));
    }

    /**
     * Update schedule
     */
    public function update(Request $request, Schedule $schedule)
    {
        if (auth()->user()->role != 1 && $schedule->event->organizer_id != auth()->id()) {
            abort(403, 'Akses ditolak');
        }

        $request->validate([
            'event_id' => 'required|exists:events,id',
            'location_id' => 'required|exists:locations,id',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $event = Event::findOrFail($request->event_id);
        if (auth()->user()->role != 1 && $event->organizer_id != auth()->id()) {
            abort(403, 'Akses ditolak');
        }

        $schedule->update($request->all());
        return redirect()->route('schedules.index')->with('success', 'Schedule updated successfully.');
    }

    /**
     * Delete schedule
     */
    public function destroy(Schedule $schedule)
    {
        if (auth()->user()->role != 1 && $schedule->event->organizer_id != auth()->id()) {
            abort(403, 'Akses ditolak');
        }
        $schedule->delete();
        return redirect()->route('schedules.index')->with('success', 'Schedule deleted successfully.');
    }
}

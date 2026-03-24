<?php
namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Event;
use App\Models\Location;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        // Fetch schedules with related event and location data to avoid N+1 issues
        $schedules = Schedule::with(['event', 'location'])->get();
        return view('schedule.index', compact('schedules'));
    }

    public function create()
    {
        $events = Event::all();
        $locations = Location::all();
        return view('schedule.create', compact('events', 'locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'location_id' => 'required|exists:locations,id',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        Schedule::create($request->all());
        return redirect()->route('schedules.index')->with('success', 'Schedule created successfully.');
    }

    public function edit(Schedule $schedule)
    {
        $events = Event::all();
        $locations = Location::all();
        return view('schedule.edit', compact('schedule', 'events', 'locations'));
    }

    public function update(Request $request, Schedule $schedule)
    {
         $request->validate([
            'event_id' => 'required|exists:events,id',
            'location_id' => 'required|exists:locations,id',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $schedule->update($request->all());
        return redirect()->route('schedules.index')->with('success', 'Schedule updated successfully.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('schedules.index')->with('success', 'Schedule deleted successfully.');
    }
}

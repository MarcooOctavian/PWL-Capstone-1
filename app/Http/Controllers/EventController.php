<?php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with(['organizer', 'category', 'location'])->get();
        return view('event.index', compact('events'));
    }

    public function create()
    {
        $categories = Category::all();
        $locations = Location::all();
        return view('event.create', compact('categories', 'locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'date' => 'required|date',
            'quota' => 'required|integer|min:1',
            'status' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['organizer_id'] = Auth::id(); // Automatically assign current user as organizer

        Event::create($data);

        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }

    public function edit(Event $event)
    {
        $categories = Category::all();
        $locations = Location::all();
        return view('event.edit', compact('event', 'categories', 'locations'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'date' => 'required|date',
            'quota' => 'required|integer|min:1',
            'status' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $event->update($request->all());

        return redirect()->route('events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }
}

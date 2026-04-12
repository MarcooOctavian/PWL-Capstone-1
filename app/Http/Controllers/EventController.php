<?php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'status' => 'required|string',
            'description' => 'nullable|string',
            'banner_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();
        $data['organizer_id'] = Auth::id(); // Assign user saat ini sebagai organizer

        // --- PROSES UPLOAD BANNER ---
        if ($request->hasFile('banner_url')) {
            $data['banner_url'] = $request->file('banner_url')->store('banners', 'public');
        }

        Event::create($data);
        return redirect()->route('admin.events.index')->with('success', 'Event created successfully.');
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
            'status' => 'required|string',
            'description' => 'nullable|string',
            'banner_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        // --- PROSES UPLOAD BANNER SAAT EDIT ---
        if ($request->hasFile('banner_url')) {
            if ($event->banner_url && Storage::disk('public')->exists($event->banner_url)) {
                Storage::disk('public')->delete($event->banner_url);
            }

            $data['banner_url'] = $request->file('banner_url')->store('banners', 'public');
        }

        $event->update($data);

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        if ($event->banner_url && Storage::disk('public')->exists($event->banner_url)) {
            Storage::disk('public')->delete($event->banner_url);
        }

        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully.');
    }

    public function publicIndex()
    {
        $events = Event::latest()->get();
        return view('user.events', compact('events'));
    }
}

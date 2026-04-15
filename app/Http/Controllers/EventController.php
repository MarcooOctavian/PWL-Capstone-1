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
        if (auth()->user()->role == 1) {
            $events = Event::with(['organizer', 'category', 'location'])->get();
        } else {
            $events = Event::with(['organizer', 'category', 'location'])
                ->where('organizer_id', auth()->id())
                ->get();
        }
        return view('event.index', compact('events'));
    }

    public function create()
    {
        abort_if(auth()->user()->role != 1, 403, 'Akses ditolak');
        $categories = Category::all();
        $locations = Location::all();
        $organizers = \App\Models\User::whereIn('role', [1, 2])->get();
        return view('event.create', compact('categories', 'locations', 'organizers'));
    }

    public function store(Request $request)
    {
        abort_if(auth()->user()->role != 1, 403, 'Akses ditolak');
        $request->validate([
            'title' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'organizer_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'status' => 'required|string',
            'description' => 'nullable|string',
            'banner_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        // --- PROSES UPLOAD BANNER ---
        if ($request->hasFile('banner_url')) {
            $data['banner_url'] = $request->file('banner_url')->store('banners', 'public');
        }

        Event::create($data);
        return redirect()->route('admin.events.index')->with('success', 'Event created successfully.');
    }

    public function edit(Event $event)
    {
        if (auth()->user()->role != 1 && $event->organizer_id != auth()->id()) {
            abort(403, 'Akses ditolak');
        }
        $categories = Category::all();
        $locations = Location::all();
        $organizers = \App\Models\User::whereIn('role', [1, 2])->get();
        return view('event.edit', compact('event', 'categories', 'locations', 'organizers'));
    }

    public function update(Request $request, Event $event)
    {
        if (auth()->user()->role != 1 && $event->organizer_id != auth()->id()) {
            abort(403, 'Akses ditolak');
        }

        $rules = [
            'title' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'date' => 'required|date',
            'status' => 'required|string',
            'description' => 'nullable|string',
            'banner_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

        if (auth()->user()->role == 1) {
            $rules['organizer_id'] = 'required|exists:users,id';
        }

        $request->validate($rules);

        $data = $request->all();
        if (auth()->user()->role != 1) {
            unset($data['organizer_id']); // Prevent organizer from reassigning
        }

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
        abort_if(auth()->user()->role != 1, 403, 'Akses ditolak');
        if ($event->banner_url && Storage::disk('public')->exists($event->banner_url)) {
            Storage::disk('public')->delete($event->banner_url);
        }

        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully.');
    }

    public function publicIndex()
    {
        $events = Event::where(function ($query) {
                $query->where('status', 'Upcoming')
                    ->orWhere('status', 'upcoming');
            })
            ->whereDate('date', '>=', now()->toDateString())
            ->latest('date')
            ->get();

        return view('user.events', compact('events'));
    }
}

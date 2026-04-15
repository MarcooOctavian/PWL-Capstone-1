<?php
namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display all locations
     */
    public function index()
    {
        $locations = Location::all();
        return view('location.index', compact('locations'));
    }

    public function create()
    {
        return view('location.create');
    }

    /**
     * Store location
     */
    public function store(Request $request)
    {
        $request->validate([
            'venue_name' => 'required|string|max:100|unique:locations,venue_name',
            'address' => 'required|string|max:200',
            'city' => 'required|string|max:100',
            'maps_url' => 'nullable|string|max:200',
        ]);

        Location::create($request->all());
        return redirect()->route('locations.index')->with('success', 'Location created successfully.');
    }

    /**
     * Edit location
     */
    public function edit(Location $location)
    {
        return view('location.edit', compact('location'));
    }

    /**
     * Update location
     */
    public function update(Request $request, Location $location)
    {
        $request->validate([
            'venue_name' => 'required|string|max:100|unique:locations,venue_name,' . $location->id,
            'address' => 'required|string|max:200',
            'city' => 'required|string|max:100',
            'maps_url' => 'nullable|string|max:200',
        ]);

        $location->update($request->all());
        return redirect()->route('locations.index')->with('success', 'Location updated successfully.');
    }

    /**
     * Delete location
     */
    public function destroy(Location $location)
    {
        $location->delete();
        return redirect()->route('locations.index')->with('success', 'Location deleted successfully.');
    }
}

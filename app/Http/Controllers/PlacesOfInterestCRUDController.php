<?php
namespace App\Http\Controllers;

use App\Models\Municipality;
use Illuminate\Http\Request;
use App\Models\InterestingPlace;
use App\Models\PlaceType;

class PlacesOfInterestCRUDController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $places = InterestingPlace::with('placeType')->orderBy('id')->paginate(10); 
        return view('places.index', compact('places'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $placeTypes = PlaceType::orderBy('name')->get();
        return view('places.create', compact('placeTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'place_type_id' => 'required|exists:place_types,id',
        ]);

        InterestingPlace::create($validated);

        return redirect()->route('places.index')->with('success', 'Place of interest created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $place = InterestingPlace::with('placeType')->findOrFail($id);
        return view('places.show', compact('place'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $place = InterestingPlace::with('placeType')->findOrFail($id);
        $placeTypes = PlaceType::orderBy('name')->get();
        return view('places.edit', compact('place', 'placeTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'place_type_id' => 'required|exists:place_types,id',
        ]);

        $place = InterestingPlace::findOrFail($id);
        $place->update($validated);

        return redirect()->route('places.index')->with('success', 'Place of interest updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $place = InterestingPlace::findOrFail($id);
        
        // Detach from treks before deleting
        $place->treks()->detach();
        $place->delete();

        return redirect()->route('places.index')->with('success', 'Place of interest deleted successfully');
    }
}
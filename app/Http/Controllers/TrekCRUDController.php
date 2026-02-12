<?php

namespace App\Http\Controllers;

use App\Models\Trek;
use App\Models\Municipality;
use Illuminate\Http\Request;
use App\Models\InterestingPlace;

class TrekCRUDController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $treks = Trek::with('municipality')->orderBy('id')->paginate(3);
        return view('treks.index', compact('treks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $municipalities = Municipality::orderBy('name')->get();
        $interestingPlaces = InterestingPlace::with('treks.municipality')->orderBy('name')->get();

        return view('treks.create', compact('municipalities', 'interestingPlaces'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'municipality_id' => 'required|exists:municipalities,id',
            'interesting_places' => 'nullable|array',
            'interesting_places.*' => 'exists:interesting_places,id',
        ]);

        $trek = Trek::create([
            'regNumber' => "T" . (Trek::max('id') + 1),
            'name' => $validated['name'],
            'municipality_id' => $validated['municipality_id'],
        ]);

        // Attach interesting places if any were selected
        if (!empty($validated['interesting_places'])) {
            $syncData = [];
            foreach ($validated['interesting_places'] as $index => $placeId) {
                $syncData[$placeId] = ['order' => $index + 1];
            }
            $trek->interestingPlaces()->attach($syncData);
        }

        return redirect()->route('trekCRUD.index')->with('success', 'Trek created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $trek = Trek::with('municipality')->findOrFail($id);
        return view('treks.show', compact('trek'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $trek = Trek::with('interestingPlaces')->findOrFail($id);
        $municipalities = Municipality::orderBy('name')->get();
        $interestingPlaces = InterestingPlace::with('treks.municipality')->orderBy('name')->get();

        return view('treks.edit', compact('trek', 'municipalities', 'interestingPlaces'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $trek = Trek::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'municipality_id' => 'required|exists:municipalities,id',
            'interesting_places' => 'nullable|array',
            'interesting_places.*' => 'exists:interesting_places,id',
        ]);

        $trek->update([
            'name' => $validated['name'],
            'municipality_id' => $validated['municipality_id'],
        ]);

        // Sync interesting places with order
        if (isset($validated['interesting_places'])) {
            $syncData = [];
            foreach ($validated['interesting_places'] as $index => $placeId) {
                $syncData[$placeId] = ['order' => $index + 1];
            }
            $trek->interestingPlaces()->sync($syncData);
        } else {
            // If no places selected, detach all
            $trek->interestingPlaces()->detach();
        }

        return redirect()->route('trekCRUD.index')->with('success', 'Trek updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $trek = Trek::findOrFail($id);
        $trek->delete();
        return redirect()->route('trekCRUD.index')->with('success', 'Trek deleted successfully');
    }
}

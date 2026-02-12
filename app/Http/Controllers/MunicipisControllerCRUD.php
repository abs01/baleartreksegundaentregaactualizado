<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Municipality;
use App\Models\Island;
use App\Models\Zone;

class MunicipisControllerCRUD extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $municipalities = Municipality::orderBy('id')->paginate(3);
        return view('municipalities.index', compact('municipalities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $municipalities = Municipality::all();
        $islands = Island::all();
        $zones = Zone::all();
        return view('municipalities.create', compact('islands', 'zones', 'municipalities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'population' => 'required|integer|min:0',
            'area' => 'required|numeric|min:0',
            'island_id' => 'required|exists:islands,id',
            'zone_id' => 'required|exists:zones,id',
        ]);
        Municipality::create($validated);
        return redirect()->route('municipalities.index')->with('success', 'Municipality created successfully');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $municipality = Municipality::with(['island', 'zone'])->findOrFail($id);
        return view('municipalities.show', compact('municipality'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $municipality = Municipality::findOrFail($id);
        $islands = Island::all();
        $zones = Zone::all();

        return view('municipalities.edit', compact('municipality', 'islands', 'zones'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'island_id' => 'required|exists:islands,id',
        'zone_id' => 'required|exists:zones,id',
    ]);

    $municipality = Municipality::findOrFail($id);
    $municipality->update($validated);

    return redirect()->route('municipalities.index')
                     ->with('success', 'Municipality updated successfully');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $municipality = Municipality::findOrFail($id);
        $municipality->delete();

        return redirect()->route('municipalities.index')
            ->with('success', 'Municipality deleted successfully');
    }
}

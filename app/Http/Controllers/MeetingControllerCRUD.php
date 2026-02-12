<?php

namespace App\Http\Controllers;

use App\Models\Trek;
use App\Models\User;
use App\Models\Zone;
use App\Models\Island;
use App\Models\Meeting;
use App\Models\Role;

use App\Models\Municipality;
use Illuminate\Http\Request;

class MeetingControllerCRUD extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $meetings = Meeting::with('trek.municipality.zone', 'trek.municipality.island')->paginate(3);
        $zones = Meeting::with('trek.municipality.zone')->get()->pluck('trek.municipality.zone')->unique()->values();
        $islands = Meeting::with('trek.municipality.island')->get()->pluck('trek.municipality.island')->unique()->values();
        return view('meetings.index', compact('meetings', 'zones', 'islands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    $treks = Trek::with('municipality')->orderBy('name')->get();
    $guides = User::where('role_id', Role::where('name', 'guia')->first()->id)->orderBy('name')->get();
    
    return view('meetings.create', compact('treks', 'guides'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'trek_id' => 'required|exists:treks,id',
            'appDateStart' => 'required|date',
            'appDateEnd' => 'required|date|after_or_equal:appDateStart',
        ]);

        Meeting::create($validated);

        return redirect()->route('meetings.index')->with('success', 'Meeting created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $meeting = Meeting::with('trek.municipality.zone', 'trek.municipality.island')->findOrFail($id);
        $zones = Meeting::with('trek.municipality.zone')->get()->pluck('trek.municipality.zone')->unique()->values();
        $islands = Meeting::with('trek.municipality.island')->get()->pluck('trek.municipality.island')->unique()->values();

        return view('meetings.show', compact('meeting', 'zones', 'islands'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $meeting = Meeting::findOrFail($id);
        $treks = Trek::with('municipality')->orderBy('name')->get();
        $guides = User::where('role_id', Role::where('name', 'guia')->first()->id)->orderBy('name')->get();
        return view('meetings.edit', compact('meeting', 'treks', 'guides'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'trek_id' => 'required|exists:treks,id',
            'appDateStart' => 'required|date',
            'appDateEnd' => 'required|date|after_or_equal:appDateStart',
        ]);

        $meeting = Meeting::findOrFail($id);
        $meeting->update($validated);

        return redirect()->route('meetings.index')->with('success', 'Meeting updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $meeting = Meeting::findOrFail($id);
        $meeting->delete();

        return redirect()->route('meetings.index')->with('success', 'Meeting deleted successfully');
    }
}

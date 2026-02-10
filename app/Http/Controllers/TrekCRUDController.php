<?php

namespace App\Http\Controllers;

use App\Models\Trek;
use Illuminate\Http\Request;

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
        return view('treks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|integer|min:1',
            'difficulty' => 'required|in:easy,medium,hard',
        ]);

        Trek::create($validated);

        // return redirect()->route('trekCRUD.index')->with('success', 'Trek created successfully');
        return redirect()->route('trek.index')->with('success', 'Trek created successfully');}

    /**
     * Display the specified resource.
     */

     public function show(Trek $trek)
    {        

        return view('treks.show', compact('trek'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $trek = Trek::findOrFail($id);
        return view('treks.edit', compact('trek'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

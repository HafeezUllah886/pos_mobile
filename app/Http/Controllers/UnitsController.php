<?php

namespace App\Http\Controllers;

use App\Models\units;
use Illuminate\Http\Request;

class UnitsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units = units::all();

        return view('products.units', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        units::create($request->all());
        return back()->with('success', 'Unit Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(units $units)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(units $units)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $unit = units::find($id);
        $unit->update($request->only('name', 'value'));
        return back()->with('success', "Unit Updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(units $units)
    {
        //
    }
}

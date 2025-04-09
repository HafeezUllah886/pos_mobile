<?php

namespace App\Http\Controllers;

use App\Models\categories;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\BackupGlobals;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cats = categories::orderBy('name', 'asc')->get();

        return view('products.categories', compact('cats'));
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
        categories::create($request->all());
        return back()->with('msg', 'Category Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(categories $categories)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(categories $categories)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        categories::find($id)->update($request->all());
        return back()->with('msg', 'Category Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(categories $categories)
    {
        //
    }
}

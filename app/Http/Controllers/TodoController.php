<?php

namespace App\Http\Controllers;

use App\Models\todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $todos = todo::orderBy('status', 'desc')->orderBy('dueDate', 'asc')->get();

        return view('todo.index', compact('todos'));
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
        todo::create($request->all());
        return back()->with('msg', "Todo Created");
    }

    /**
     * Display the specified resource.
     */
    public function delete($id)
    {
        todo::find($id)->delete();
        session()->forget('confirmed_password');
        return to_route('todos.index')->with('success', 'Todo Deleted');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function status($id)
    {
        $todo = todo::find($id);
        $todo->update(
            [
                'status' => "Completed",
            ]
        );

        return back()->with('msg', "Todo Marked as Completed");
    }

}

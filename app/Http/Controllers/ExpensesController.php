<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\expenseCategories;
use App\Models\expenses;
use App\Models\transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpensesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenses = expenses::orderby('id', 'desc')->get();
        $accounts = accounts::business()->get();
        $categories = expenseCategories::all();
        return view('Finance.expense.index', compact('expenses', 'accounts', 'categories'));
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
        try
        {
            DB::beginTransaction();
            $ref = getRef();
            expenses::create(
                [
                    'accountID' => $request->accountID,
                    'catID' => $request->catID,
                    'amount' => $request->amount,
                    'date' => $request->date,
                    'notes' => $request->notes,
                    'refID' => $ref,
                ]
            );

            createTransaction($request->accountID, $request->date, 0, $request->amount, "Expense - ".$request->notes, $ref);

            DB::commit();
            return back()->with('success', 'Expense Saved');
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(expenses $expenses)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(expenses $expenses)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, expenses $expenses)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($ref)
    {
        try
        {
            DB::beginTransaction();
            expenses::where('refID', $ref)->delete();
            transactions::where('refID', $ref)->delete();
            DB::commit();
            session()->forget('confirmed_password');
            return redirect()->route('expenses.index')->with('success', "Expense Deleted");
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            session()->forget('confirmed_password');
            return redirect()->route('expenses.index')->with('error', $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\deposit_withdraw;
use App\Models\transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepositWithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trans = deposit_withdraw::orderBy('id', 'desc')->get();
        $accounts = accounts::orderby('type', 'asc')->orderby('title', 'asc')->get();

        return view('Finance.deposit_withdraw.index', compact('trans', 'accounts'));
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
            deposit_withdraw::create(
                [
                    'accountID' => $request->accountID,
                    'date' => $request->date,
                    'type' => $request->type,
                    'amount' => $request->amount,
                    'notes' => $request->notes,
                    'refID' => $ref
                ]
            );

            if($request->type == 'Deposit')
            {
                createTransaction($request->accountID, $request->date, $request->amount, 0, "Deposit: ".$request->notes, $ref);
            }
            else
            {
                createTransaction($request->accountID, $request->date, 0, $request->amount, "Withdraw: ".$request->notes, $ref);
            }

            DB::commit();
            return back()->with('success', "Transaction Created");
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
    public function show(deposit_withdraw $deposit_withdraw)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(deposit_withdraw $deposit_withdraw)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, deposit_withdraw $deposit_withdraw)
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
            deposit_withdraw::where('refID', $ref)->delete();
            transactions::where('refID', $ref)->delete();
            DB::commit();
            session()->forget('confirmed_password');
            return redirect()->route('deposit_withdraw.index')->with('success', "Transaction Deleted");
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            session()->forget('confirmed_password');
            return redirect()->route('deposit_withdraw.index')->with('error', $e->getMessage());
        }
    }
}

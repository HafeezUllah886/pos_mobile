<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\sale_payments;
use App\Models\sales;
use App\Models\transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalePaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $sale = sales::with('details', 'payments')->find($id);
        $amount = $sale->total;
        $paid = $sale->payments->sum('amount');
        $due = $amount - $paid;

        $accounts = accounts::business()->get();

        return view('sales.payments', compact('sale', 'due', 'accounts'));
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
        try{
            DB::beginTransaction();
            $ref = getRef();
            $sale = sales::find($request->salesID);
            sale_payments::create(
                [
                    'salesID'       => $sale->id,
                    'accountID'     => $request->accountID,
                    'date'          => $request->date,
                    'amount'        => $request->amount,
                    'notes'         => $request->notes,
                    'refID'         => $ref,
                ]
            );

            createTransaction($request->accountID, $request->date,$request->amount, 0, "Payment of Inv No. $sale->id", $ref);
            createTransaction($sale->customerID, $request->date,$request->amount, 0, "Payment of Inv No. $sale->id", $ref);

            DB::commit();
            return back()->with('success', "Payment Saved");
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $payment = sale_payments::find($id);

        return view('sales.receipt', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(sale_payments $sale_payments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, sale_payments $sale_payments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, $ref)
    {
        try
        {
            DB::beginTransaction();
            sale_payments::where('refID', $ref)->delete();
            transactions::where('refID', $ref)->delete();
            DB::commit();
            session()->forget('confirmed_password');
            return redirect()->route('salePayment.index', $id)->with('success', "Sale Payment Deleted");
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            session()->forget('confirmed_password');
            return redirect()->route('salePayment.index', $id)->with('error', $e->getMessage());
        }
    }
}

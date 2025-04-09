<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\purchase;
use App\Models\purchase_payments;
use App\Models\transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchasePaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $purchase = purchase::with('details', 'payments')->find($id);
        $amount = $purchase->total;
        $paid = $purchase->payments->sum('amount');
        $due = $amount - $paid;

        $accounts = accounts::business()->get();

        return view('purchase.payments', compact('purchase', 'due', 'accounts'));
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
            $purchase = purchase::find($request->purchaseID);
            purchase_payments::create(
                [
                    'purchaseID'    => $purchase->id,
                    'accountID'     => $request->accountID,
                    'date'          => $request->date,
                    'amount'        => $request->amount,
                    'notes'         => $request->notes,
                    'refID'         => $ref,
                ]
            );

            createTransaction($request->accountID, $request->date, 0, $request->amount, "Payment of Purchase No. $purchase->id", $ref);
            createTransaction($purchase->vendorID, $request->date, $request->amount, 0, "Payment of Purchase No. $purchase->id", $ref);

            DB::commit();
            return back()->with('success', "Payment Saved");
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
    public function show(purchase_payments $purchase_payments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(purchase_payments $purchase_payments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, purchase_payments $purchase_payments)
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
            purchase_payments::where('refID', $ref)->delete();
            transactions::where('refID', $ref)->delete();
            DB::commit();
            session()->forget('confirmed_password');
            return redirect()->route('purchasePayment.index', $id)->with('success', "Purchase Payment Deleted");
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            session()->forget('confirmed_password');
            return redirect()->route('purchasePayment.index', $id)->with('error', $e->getMessage());
        }
    }
}

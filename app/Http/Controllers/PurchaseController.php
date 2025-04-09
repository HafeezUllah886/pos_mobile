<?php

namespace App\Http\Controllers;

use App\Http\Middleware\confirmPassword;
use App\Models\accounts;
use App\Models\categories;
use App\Models\products;
use App\Models\purchase;
use App\Models\purchase_details;
use App\Models\purchase_payments;
use App\Models\stock;
use App\Models\transactions;
use App\Models\units;
use App\Models\warehouses;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;

class PurchaseController extends Controller
{
    public function __construct()
    {
        // Apply middleware to the edit method
        $this->middleware(confirmPassword::class)->only('edit');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = purchase::with('payments')->orderby('id', 'desc')->paginate(10);
        return view('purchase.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = products::orderby('name', 'asc')->get();
        $warehouses = warehouses::all();
        $vendors = accounts::vendor()->get();
        $accounts = accounts::business()->get();
        $cats = categories::orderBy('name', 'asc')->get();
        return view('purchase.create', compact('products', 'warehouses', 'vendors', 'accounts', 'cats'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try
        {
            if($request->isNotFilled('id'))
            {
                throw new Exception('Please Select Atleast One Product');
            }
            DB::beginTransaction();
            $ref = getRef();
            $purchase = purchase::create(
                [
                  'vendorID'        => $request->vendorID,
                  'date'            => $request->date,
                  'notes'           => $request->notes,
                  'discount'        => $request->discount,
                  'dc'              => $request->dc,
                  'vendorName'      => $request->vendorName,
                  'inv'             => $request->inv,
                  'refID'           => $ref,
                ]
            );

            $ids = $request->id;
            dashboard();
            $total = 0;
            foreach($ids as $key => $id)
            {
                if($request->qty[$key] > 0)
                {
                    $qty = $request->qty[$key];
                $pprice = $request->pprice[$key];
                $price = $request->price[$key];
                $amount = $pprice * $qty;
                $total += $amount;

                purchase_details::create(
                    [
                        'purchaseID'    => $purchase->id,
                        'productID'     => $id,
                        'pprice'        => $pprice,
                        'price'         => $price,
                        'qty'           => $qty,
                        'amount'        => $amount,
                        'date'          => $request->date,
                        'refID'         => $ref,
                        'warehouseID'   => $request->warehouse[$key],
                    ]
                );
                createStock($id, $qty, 0, $request->date, "Purchased in $purchase->id", $ref, $request->warehouse[$key]);

                $product = products::find($id);
                $product->update(
                    [
                        'pprice' => $pprice,
                        'price'  => $price,
                    ]
                );
                }
            }

            $net = ($total + $request->dc) - $request->discount;

            $purchase->update(
                [

                    'total'       => $net,
                ]
            );

            if($request->status == 'paid')
            {
                purchase_payments::create(
                    [
                        'purchaseID'    => $purchase->id,
                        'accountID'     => $request->accountID,
                        'date'          => $request->date,
                        'amount'        => $net,
                        'notes'         => "Full Paid",
                        'refID'         => $ref,
                    ]
                );

                createTransaction($request->accountID, $request->date, 0, $net, "Payment of Purchase No. $purchase->id", $ref);
                createTransaction($request->vendorID, $request->date, $net, $net, "Payment of Purchase No. $purchase->id", $ref);
            }
            elseif($request->status == 'advanced')
            {
                $balance = getAccountBalance($request->vendorID);
                if($net > $balance)
                {
                    createTransaction($request->vendorID, $request->date, 0, $net, "Pending Amount of Purchase No. $purchase->id", $ref);
                    DB::commit();
                    return back()->with('success', "Purchase Created: Balance was not enough moved to unpaid / pending");
                }
                purchase_payments::create(
                    [
                        'purchaseID'    => $purchase->id,
                        'accountID'     => $request->accountID,
                        'date'          => $request->date,
                        'amount'        => $net,
                        'notes'         => "Full Paid",
                        'refID'         => $ref,
                    ]
                );

                createTransaction($request->vendorID, $request->date, 0, $net, "Purchase No. $purchase->id", $ref);
            }
            else
            {
                createTransaction($request->vendorID, $request->date, 0, $net, "Pending Amount of Purchase No. $purchase->id", $ref);
            }
            DB::commit();
            return back()->with('success', "Purchase Created");

        }
        catch(\Exception $e)
        {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(purchase $purchase)
    {
        return view('purchase.view', compact('purchase'));
    }

    public function pdf($id)
    {
        $purchase = purchase::find($id);
        $pdf = Pdf::loadview('purchase.pdf', compact('purchase'));

        return $pdf->download("Purchase Vouchar No. $purchase->id.pdf");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(purchase $purchase)
    {
        $products = products::orderby('name', 'asc')->get();
        $warehouses = warehouses::all();
        $vendors = accounts::vendor()->get();
        $accounts = accounts::business()->get();

        return view('purchase.edit', compact('products', 'warehouses', 'vendors', 'accounts', 'purchase'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, purchase $purchase)
    {
        try
        {
            if($request->isNotFilled('id'))
            {
                throw new Exception('Please Select Atleast One Product');
            }
            DB::beginTransaction();
            foreach($purchase->payments as $payment)
            {
                transactions::where('refID', $payment->refID)->delete();
                $payment->delete();
            }
            foreach($purchase->details as $product)
            {
                stock::where('refID', $product->refID)->delete();
                $product->delete();
            }
            transactions::where('refID', $purchase->refID)->delete();

            $purchase->update(
                [
                'vendorID'        => $request->vendorID,
                  'date'            => $request->date,
                  'notes'           => $request->notes,
                  'discount'        => $request->discount,
                  'dc'              => $request->dc,
                  'vendorName'      => $request->vendorName,
                  'inv'             => $request->inv,
                  ]
            );

            $ids = $request->id;
            $ref = $purchase->refID;

            $total = 0;
            foreach($ids as $key => $id)
            {
                if($request->qty[$key] > 0)
                {
                    $qty = $request->qty[$key];
                $pprice = $request->pprice[$key];
                $price = $request->price[$key];
                $amount = $pprice * $qty;
                $total += $amount;

                purchase_details::create(
                    [
                        'purchaseID'    => $purchase->id,
                        'productID'     => $id,
                        'pprice'        => $pprice,
                        'price'         => $price,
                        'qty'           => $qty,
                        'amount'        => $amount,
                        'date'          => $request->date,
                        'refID'         => $ref,
                        'warehouseID'   => $request->warehouse[$key],
                    ]
                );
                createStock($id, $qty, 0, $request->date, "Purchased in $purchase->id", $ref, $request->warehouse[$key]);

                $product = products::find($id);
                $product->update(
                    [
                        'pprice' => $pprice,
                        'price'  => $price,
                    ]
                );
                }
            }

            $net = ($total + $request->dc) - $request->discount;

            $purchase->update(
                [

                    'total'       => $net,
                ]
            );

            if($request->status == 'paid')
            {
                purchase_payments::create(
                    [
                        'purchaseID'    => $purchase->id,
                        'accountID'     => $request->accountID,
                        'date'          => $request->date,
                        'amount'        => $net,
                        'notes'         => "Full Paid",
                        'refID'         => $ref,
                    ]
                );
                createTransaction($request->accountID, $request->date, 0, $net, "Payment of Purchase No. $purchase->id", $ref);
                createTransaction($request->vendorID, $request->date, $net, $net, "Payment of Purchase No. $purchase->id", $ref);
            }
            elseif($request->status == 'advanced')
            {
                $balance = getAccountBalance($request->vendorID);
                if($net > $balance)
                {
                    createTransaction($request->vendorID, $request->date, 0, $net, "Pending Amount of Purchase No. $purchase->id", $ref);
                    DB::commit();
                    return back()->with('success', "Purchase Created: Balance was not enough moved to unpaid / pending");
                }
                purchase_payments::create(
                    [
                        'purchaseID'    => $purchase->id,
                        'accountID'     => $request->accountID,
                        'date'          => $request->date,
                        'amount'        => $net,
                        'notes'         => "Full Paid",
                        'refID'         => $ref,
                    ]
                );
                createTransaction($request->vendorID, $request->date, 0, $net, "Purchase No. $purchase->id", $ref);
            }
            else
            {
                createTransaction($request->vendorID, $request->date, 0, $net, "Pending Amount of Purchase No. $purchase->id", $ref);
            }
            DB::commit();
            session()->forget('confirmed_password');
            return to_route('purchase.index')->with('success', "Purchase Updated");
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        try
        {
            DB::beginTransaction();
            $purchase = purchase::find($id);
            foreach($purchase->payments as $payment)
            {
                transactions::where('refID', $payment->refID)->delete();
                $payment->delete();
            }
            foreach($purchase->details as $product)
            {
                stock::where('refID', $product->refID)->delete();
                $product->delete();
            }
            transactions::where('refID', $purchase->refID)->delete();
            $purchase->delete();
            DB::commit();
            session()->forget('confirmed_password');
            return redirect()->route('purchase.index')->with('success', "Purchase Deleted");
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            session()->forget('confirmed_password');
            return redirect()->route('purchase.index')->with('error', $e->getMessage());
        }
    }

    public function getSignleProduct($id)
    {
        $product = products::find($id);
        return $product;
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Middleware\confirmPassword;
use App\Models\accounts;
use App\Models\categories;
use App\Models\products;
use App\Models\purchase_details;
use App\Models\sale_details;
use App\Models\sale_payments;
use App\Models\sales;
use App\Models\stock;
use App\Models\transactions;
use App\Models\warehouses;
use Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;

class SalesController extends Controller
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
        $sales = sales::with('payments')->orderby('id', 'desc')->paginate(10);
        return view('sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = purchase_details::with('product')->where('status', 'Available')->get();
        $customers = accounts::customer()->get();
        $accounts = accounts::business()->get();
        $cats = categories::orderBy('name', 'asc')->get();
        return view('sales.create', compact('products', 'customers', 'accounts', 'cats'));
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
            $sale = sales::create(
                [
                  'customerID'      => $request->customerID,
                  'date'            => $request->date,
                  'notes'           => $request->notes,
                  'customerName'    => $request->customerName,
                  'refID'           => $ref,
                ]
            );

            $ids = $request->id;

            $total = 0;
            foreach($ids as $key => $id)
            {
               
                $price = $request->price[$key];
                $total += $price;
                $purchase = purchase_details::find($id);
                sale_details::create(
                    [
                        'salesID'       => $sale->id,
                        'productID'     => $purchase->productID,
                        'purchaseID'    => $purchase->id,
                        'price'         => $price,
                        'imei'          => $request->imei[$key],
                        'date'          => $request->date,
                        'refID'         => $ref,
                    ]
                );
                $purchase->update(
                    [
                        'status' => 'Sold',
                        'saleID' => $sale->id,
                    ]
                );
            }
            $sale->update(
                [
                    'total'   => $total,
                ]
            );

            if($request->status == 'paid')
            {
                sale_payments::create(
                    [
                        'salesID'       => $sale->id,
                        'accountID'     => $request->accountID,
                        'date'          => $request->date,
                        'amount'        => $total,
                        'notes'         => "Full Paid",
                        'refID'         => $ref,
                    ]
                );
                createTransaction($request->accountID, $request->date, $total, 0, "Payment of Inv No. $sale->id", $ref);
                createTransaction($request->customerID, $request->date, $total, $total, "Payment of Inv No. $sale->id", $ref);
            }
            elseif($request->status == 'partial')
            {
                $paid = $request->paid;
                if($paid < 1)
                {
                    createTransaction($request->customerID, $request->date, $total, 0, "Pending Amount of Inv No. $sale->id", $ref);
                    DB::commit();
                    return back()->with('success', "Sale Created: Bill moved to unpaid / pending");
                }
                else
                {
                    sale_payments::create(
                        [
                            'salesID'       => $sale->id,
                            'accountID'     => $request->accountID,
                            'date'          => $request->date,
                            'amount'        => $paid,
                            'notes'         => "Parial Payment",
                            'refID'         => $ref,
                        ]
                    );

                    createTransaction($request->customerID, $request->date, $total, $paid, "Partial Payment of Inv No. $sale->id", $ref);
                    createTransaction($request->accountID, $request->date, $paid, 0, "Partial Payment of Inv No. $sale->id", $ref);
                }

            }
            else
            {
                createTransaction($request->customerID, $request->date, $total, 0, "Pending Amount of Inv No. $sale->id", $ref);
            }

           DB::commit();
            return to_route('sale.show', $sale->id)->with('success', "Sale Created");

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
    public function show(sales $sale)
    {
        return view('sales.view', compact('sale'));
    }

    public function pdf($id)
    {
        $sale = sales::find($id);
        $pdf = Pdf::loadview('sales.pdf', compact('sale'));
    return $pdf->download("Invoice No. $sale->id.pdf");
    }


    public function edit(sales $sale)
    {
        $products =  purchase_details::with('product')->where('status', 'Available')->get();
        $customers = accounts::customer()->get();
        $accounts = accounts::business()->get();
        session()->forget('confirmed_password');
        return view('sales.edit', compact('products', 'customers', 'accounts', 'sale'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        dashboard();
        try
        {
            DB::beginTransaction();
            $sale = sales::find($id);
            foreach($sale->payments as $payment)
            {
                transactions::where('refID', $payment->refID)->delete();
                $payment->delete();
            }
            foreach($sale->details as $product)
            {
                $purchase = purchase_details::find($product->purchaseID);
                $purchase->status = 'Available';
                $purchase->update();
                $product->delete();
            }
            transactions::where('refID', $sale->refID)->delete();
            $ref = $sale->refID;
            $sale->update(
                [
                  'customerID'  => $request->customerID,
                  'date'        => $request->date,
                  'notes'       => $request->notes,
                  'customerName'=> $request->customerName,
                  ]
            );

            $ids = $request->id;

            $total = 0;
            foreach($ids as $key => $id)
            {
               
                $price = $request->price[$key];
                $total += $price;
                $purchase = purchase_details::find($id);
                sale_details::create(
                    [
                        'salesID'       => $sale->id,
                        'productID'     => $purchase->productID,
                        'purchaseID'    => $purchase->id,
                        'price'         => $price,
                        'imei'          => $request->imei[$key],
                        'date'          => $request->date,
                        'refID'         => $ref,
                    ]
                );
                $purchase->update(
                    [
                        'status' => 'Sold',
                        'saleID' => $sale->id,
                    ]
                );

            }

            $sale->update(
                [
                    'total'   => $total,
                ]
            );
            if($request->status == 'paid')
            {
                sale_payments::create(
                    [
                        'salesID'       => $sale->id,
                        'accountID'     => $request->accountID,
                        'date'          => $request->date,
                        'amount'        => $total,
                        'notes'         => "Full Paid",
                        'refID'         => $ref,
                    ]
                );
                createTransaction($request->accountID, $request->date, $total, 0, "Payment of Inv No. $sale->id", $ref);
                createTransaction($request->customerID, $request->date, $total, $total, "Payment of Inv No. $sale->id", $ref);
            }
            elseif($request->status == 'partial')
            {
                $paid = $request->paid;
                if($paid < 1)
                {
                    createTransaction($request->customerID, $request->date, $total, 0, "Pending Amount of Inv No. $sale->id", $ref);
                    DB::commit();
                    return back()->with('success', "Sale Created: Bill moved to unpaid / pending");
                }
                else
                {
                    sale_payments::create(
                        [
                            'salesID'       => $sale->id,
                            'accountID'     => $request->accountID,
                            'date'          => $request->date,
                            'amount'        => $paid,
                            'notes'         => "Parial Payment",
                            'refID'         => $ref,
                        ]
                    );

                    createTransaction($request->customerID, $request->date, $total, $paid, "Partial Payment of Inv No. $sale->id", $ref);
                    createTransaction($request->accountID, $request->date, $paid, 0, "Partial Payment of Inv No. $sale->id", $ref);
                }
            }
            else
            {
                createTransaction($request->customerID, $request->date, $total, 0, "Pending Amount of Inv No. $sale->id", $ref);
            }

            DB::commit();
            return to_route('sale.index')->with('success', "Sale Updated");
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return to_route('sale.index')->with('error', $e->getMessage());
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
            $sale = sales::find($id);
            foreach($sale->payments as $payment)
            {
                transactions::where('refID', $payment->refID)->delete();
                $payment->delete();
            }
            foreach($sale->details as $product)
            {
                $purchase = purchase_details::find($product->purchaseID);
                $purchase->status = 'Available';
                $purchase->update();
                $product->delete();
            }
            transactions::where('refID', $sale->refID)->delete();
            $sale->delete();
            DB::commit();
            session()->forget('confirmed_password');
            return to_route('sale.index')->with('success', "Sale Deleted");
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            session()->forget('confirmed_password');
            return to_route('sale.index')->with('error', $e->getMessage());
        }
    }

    public function getSignleProduct($id)
    {
        $product = purchase_details::with('product')->find($id);
        return $product;
    }
}

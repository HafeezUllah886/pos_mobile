<?php

namespace App\Http\Controllers;

use App\Http\Middleware\confirmPassword;
use App\Models\accounts;
use App\Models\categories;
use App\Models\products;
use App\Models\sale_details;
use App\Models\sale_payments;
use App\Models\sales;
use App\Models\salesman;
use App\Models\stock;
use App\Models\transactions;
use App\Models\units;
use App\Models\warehouses;
use Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Browsershot\Browsershot;
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
        $products = products::orderby('name', 'asc')->get();
        $warehouses = warehouses::all();
        $customers = accounts::customer()->get();
        $accounts = accounts::business()->get();
        $cats = categories::orderBy('name', 'asc')->get();
        return view('sales.create', compact('products', 'warehouses', 'customers', 'accounts', 'cats'));
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
                  'discount'        => $request->discount,
                  'dc'              => $request->dc,
                  'customerName'    => $request->customerName,
                  'refID'           => $ref,
                ]
            );

            $ids = $request->id;

            $total = 0;
            foreach($ids as $key => $id)
            {
                if($request->amount[$key] > 0)
                {
                    $qty = $request->qty[$key];
                $price = $request->price[$key];
                $total += $request->amount[$key];
                sale_details::create(
                    [
                        'salesID'       => $sale->id,
                        'productID'     => $id,
                        'price'         => $price,
                        'warehouseID'   => $request->warehouse[$key],
                        'qty'           => $qty,
                        'amount'        => $request->amount[$key],
                        'date'          => $request->date,
                        'refID'         => $ref,
                    ]
                );
                createStock($id,0, $qty, $request->date, "Sold in Inv # $sale->id", $ref, $request->warehouse[$key]);
                }

            }

            $discount = $request->discount;
            $dc = $request->dc;
            $net = ($total + $dc) - $discount;

            $sale->update(
                [
                    'total'   => $net,
                ]
            );

            if($request->status == 'paid')
            {
                sale_payments::create(
                    [
                        'salesID'       => $sale->id,
                        'accountID'     => $request->accountID,
                        'date'          => $request->date,
                        'amount'        => $net,
                        'notes'         => "Full Paid",
                        'refID'         => $ref,
                    ]
                );
                createTransaction($request->accountID, $request->date, $net, 0, "Payment of Inv No. $sale->id", $ref);
                createTransaction($request->customerID, $request->date, $net, $net, "Payment of Inv No. $sale->id", $ref);
            }
            elseif($request->status == 'advanced')
            {
                $balance = getAccountBalance($request->customerID);
                if($net < $balance)
                {
                    createTransaction($request->customerID, $request->date, $net, 0, "Pending Amount of Inv No. $sale->id", $ref);
                    DB::commit();
                    return back()->with('success', "Sale Created: Balance was not enough moved to unpaid / pending");
                }
                else
                {
                    sale_payments::create(
                        [
                            'salesID'       => $sale->id,
                            'accountID'     => $request->accountID,
                            'date'          => $request->date,
                            'amount'        => $net,
                            'notes'         => "Full Paid",
                            'refID'         => $ref,
                        ]
                    );

                    createTransaction($request->customerID, $request->date, $net, 0, "Inv No. $sale->id", $ref);
                }

            }
            elseif($request->status == 'partial')
            {
                $paid = $request->paid;
                if($paid < 1)
                {
                    createTransaction($request->customerID, $request->date, $net, 0, "Pending Amount of Inv No. $sale->id", $ref);
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

                    createTransaction($request->customerID, $request->date, $net, $paid, "Partial Payment of Inv No. $sale->id", $ref);
                    createTransaction($request->accountID, $request->date, $paid, 0, "Partial Payment of Inv No. $sale->id", $ref);
                }

            }
            else
            {
                createTransaction($request->customerID, $request->date, $net, 0, "Pending Amount of Inv No. $sale->id", $ref);
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
        $products = products::orderby('name', 'asc')->get();
        $warehouses = warehouses::all();
        $customers = accounts::customer()->get();
        $accounts = accounts::business()->get();
        session()->forget('confirmed_password');
        return view('sales.edit', compact('products', 'warehouses', 'customers', 'accounts', 'sale'));
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
                stock::where('refID', $product->refID)->delete();
                $product->delete();
            }
            transactions::where('refID', $sale->refID)->delete();
            $ref = $sale->refID;
            $sale->update(
                [
                    'customerID'  => $request->customerID,
                  'date'        => $request->date,
                  'notes'       => $request->notes,
                  'discount'    => $request->discount,
                  'customerName'=> $request->customerName,
                  'dc'          => $request->dc,
                  ]
            );

            $ids = $request->id;

            $total = 0;
            foreach($ids as $key => $id)
            {
                if($request->amount[$key] > 0)
                {
                    $qty = $request->qty[$key];
                $price = $request->price[$key];
                $total += $request->amount[$key];
                sale_details::create(
                    [
                        'salesID'       => $sale->id,
                        'productID'     => $id,
                        'price'         => $price,
                        'warehouseID'   => $request->warehouse[$key],
                        'qty'           => $qty,
                        'amount'        => $request->amount[$key],

                        'date'          => $request->date,
                        'refID'         => $ref,
                    ]
                );
                createStock($id,0, $qty, $request->date, "Sold in Inv # $sale->id", $ref, $request->warehouse[$key]);
                }
            }

            $discount = $request->discount;
            $dc = $request->dc;
            $net = ($total + $dc) - $discount;

            $sale->update(
                [
                    'total'   => $net,
                ]
            );

            if($request->status == 'paid')
            {
                sale_payments::create(
                    [
                        'salesID'       => $sale->id,
                        'accountID'     => $request->accountID,
                        'date'          => $request->date,
                        'amount'        => $net,
                        'notes'         => "Full Paid",
                        'refID'         => $ref,
                    ]
                );
                createTransaction($request->accountID, $request->date, $net, 0, "Payment of Inv No. $sale->id", $ref);
                createTransaction($request->customerID, $request->date, $net, $net, "Payment of Inv No. $sale->id", $ref);
            }
            elseif($request->status == 'advanced')
            {
                $balance = getAccountBalance($request->customerID);
                if($net < $balance)
                {
                    createTransaction($request->customerID, $request->date, $net, 0, "Pending Amount of Inv No. $sale->id", $ref);
                    DB::commit();
                    return back()->with('success', "Sale Created: Balance was not enough moved to unpaid / pending");
                }
                else
                {
                    sale_payments::create(
                        [
                            'salesID'       => $sale->id,
                            'accountID'     => $request->accountID,
                            'date'          => $request->date,
                            'amount'        => $net,
                            'notes'         => "Full Paid",
                            'refID'         => $ref,
                        ]
                    );

                    createTransaction($request->customerID, $request->date, $net, 0, "Inv No. $sale->id", $ref);
                }

            }
            elseif($request->status == 'partial')
            {
                $paid = $request->paid;
                if($paid < 1)
                {
                    createTransaction($request->customerID, $request->date, $net, 0, "Pending Amount of Inv No. $sale->id", $ref);
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

                    createTransaction($request->customerID, $request->date, $net, $paid, "Partial Payment of Inv No. $sale->id", $ref);
                    createTransaction($request->accountID, $request->date, $paid, 0, "Partial Payment of Inv No. $sale->id", $ref);
                }

            }
            else
            {
                createTransaction($request->customerID, $request->date, $net, 0, "Pending Amount of Inv No. $sale->id", $ref);
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
                stock::where('refID', $product->refID)->delete();
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
        $product = products::with('unit')->find($id);
        return $product;
    }
}

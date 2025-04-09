<?php

namespace App\Http\Controllers;

use App\Models\categories;
use App\Models\products;
use App\Models\quotation;
use App\Models\quotationDetails;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $quotations = quotation::orderBy('id', 'desc')->get();
        return view('quotation.index', compact('quotations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = products::orderby('name', 'asc')->get();
        $cats = categories::orderBy('name', 'asc')->get();
        return view('quotation.create', compact('products', 'cats'));
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
            $quot = quotation::create(
                [
                  'customerName'        => $request->customer,
                  'customerAddress'     => $request->address,
                  'notes'               => $request->notes,
                  'discount'            => $request->discount,
                  'dc'                  => $request->dc,
                  'date'                => $request->date,
                  'validTill'           => $request->valid,
                ]
            );

            $ids = $request->id;

            $total = 0;
            foreach($ids as $key => $id)
            {
                if( $request->amount[$key] > 0)
                {
                    $qty = $request->qty[$key];
                $price = $request->price[$key];
                $total += $request->amount[$key];
                quotationDetails::create(
                    [
                        'quotID'       => $quot->id,
                        'productID'     => $id,
                        'price'         => $price,
                        'qty'           => $qty,
                        'amount'        => $request->amount[$key],
                    ]
                );
                }

            }

            $discount = $request->discount;
            $dc = $request->dc;
            $net = ($total + $dc) - $discount;

            $quot->update(
                [
                    'total'   => $net,
                ]
            );

           DB::commit();
            return to_route('quotation.show', $quot->id)->with('success', "Quotation Created");

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
    public function show($id)
    {
        $quot = quotation::find($id);
        return view('quotation.view', compact('quot'));
    }

    public function pdf($id)
    {
        $quot = quotation::find($id);
        $pdf = Pdf::loadview('quotation.pdf', compact('quot'));
    return $pdf->download("Quotation No. $quot->id.pdf");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(quotation $quotation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, quotation $quotation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try
        {
            DB::beginTransaction();
            $quot = quotation::find($id);
            foreach($quot->details as $product)
            {
                $product->delete();
            }
            $quot->delete();
            DB::commit();
            session()->forget('confirmed_password');
            return to_route('quotation.index')->with('success', "Quotation Deleted");
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            session()->forget('confirmed_password');
            return to_route('quotation.index')->with('error', $e->getMessage());
        }
    }
}

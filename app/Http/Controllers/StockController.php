<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\purchase_details;
use App\Models\sale_details;
use App\Models\sales;
use App\Models\stock;
use App\Models\units;
use App\Models\warehouses;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(request $request)
    {
        $status = $request->status;
        $products = purchase_details::with('product')->where('status', $status)->get();
        foreach($products as $product)
        {
            if($product->status == "Available")
            {
                $product->person = $product->purchase->vendor->title;
                $product->price = $product->price;
            }
            else
            {
                $sale = sales::find($product->saleID);
                $sale_details = sale_details::where('salesID', $sale->id)->where('imei', $product->imei)->first();
                $product->person = $sale->customer->title;
                $product->price = $sale_details->price;
            }
        }
        return view('stock.index', compact('products','status'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $products = products::all();
        foreach($products as $product)
        {
            $purchases = purchase_details::where('productID', $product->id)->where('status', 'Available');
            $product->stock = $purchases->count();
            $product->value = $purchases->sum('price');
        }
        return view('stock.by_product', compact('products'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(stock $stock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, stock $stock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(stock $stock)
    {
        //
    }
}

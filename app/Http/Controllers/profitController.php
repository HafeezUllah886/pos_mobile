<?php

namespace App\Http\Controllers;

use App\Models\expenses;
use App\Models\products;
use App\Models\purchase_details;
use App\Models\sale_details;
use Illuminate\Http\Request;

class profitController extends Controller
{
    public function index()
    {
        return view('reports.profit.index');
    }

    public function data($from, $to)
    {
        $products = products::all();
        $data = [];
        foreach($products as $product)
        {

            $purchaseRate = avgPurchasePrice($from, $to, $product->id);
            $saleRate = avgSalePrice($from, $to, $product->id);
            $purchased = purchase_details::where('productID', $product->id)->whereBetween('date', [$from, $to])->count();
            $sold = sale_details::where('productID', $product->id)->whereBetween('date', [$from, $to])->count();
            $sales = sale_details::where('productID', $product->id)->whereBetween('date', [$from, $to])->pluck('imei')->toArray();
            $sales_amount = sale_details::where('productID', $product->id)->whereBetween('date', [$from, $to])->sum('price');
            $purchase_amount = purchase_details::where('productID', $product->id)->whereIn('imei', $sales)->sum('price');
            $stock = purchase_details::where('productID', $product->id)->where('status', 'Available')->count();

            $profit = $sales_amount - $purchase_amount;
            
            $stockValue = purchase_details::where('productID', $product->id)->where('status', 'Available')->sum('price');

            $data[] = ['name' => $product->name, 'purchaseRate' => $purchaseRate, 'saleRate' => $saleRate, 'sold' => $sold, 'profit' => $profit, 'stock' => $stock, 'stockValue' => $stockValue];
        }

        $expenses = expenses::whereBetween('date', [$from, $to])->sum('amount');

        return view('reports.profit.details', compact('from', 'to', 'data', 'expenses'));
    }
}

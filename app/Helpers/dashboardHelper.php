<?php

use App\Models\accounts;
use App\Models\expenses;
use App\Models\products;
use App\Models\purchase;
use App\Models\purchase_details;
use App\Models\sale_details;
use App\Models\sales;
use Illuminate\Support\Facades\DB;

function totalSales()
{
    return sale_details::whereDate('date', now())->sum('price');
}

function totalPurchases()
{
   return purchase_details::whereDate('date', now())->sum('price');
}

function totalStock()
{
    $stock = purchase_details::where('status', 'Available')->count();
   
    return $stock;
}

function myBalance()
{
    $accounts = accounts::where('type', 'Business')->get();
    $balance = 0;
    foreach($accounts as $account)
    {
        $balance += getAccountBalance($account->id);
    }

    return $balance;
}

function customerBalance()
{
    $accounts = accounts::where('type', 'Customer')->get();
    $balance = 0;
    foreach($accounts as $account)
    {
        $balance += getAccountBalance($account->id);
    }

    return $balance;
}

function dashboard()
{
    $domains = config('app.domains');
    $current_domain = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'];
    if (!in_array($current_domain, $domains)) {
        die("Invalid Configrations");
    }

    $files = config('app.files');
    $file2 = filesize(public_path('assets/images/Header.png'));

    if($files[1] != $file2)
    {
        abort(500, "Something Went Wrong!");
    }

    $databases = config('app.databases');
    $current_db = DB::connection()->getDatabaseName();
    if (!in_array($current_db, $databases)) {
        abort(500, "Connection Failed!");
    }
}

function vendorBalance()
{
    $accounts = accounts::where('type', 'Vendor')->get();
    $balance = 0;
    foreach($accounts as $account)
    {
        $balance += getAccountBalance($account->id);
    }

    return $balance;
}


function dailyProfit()
{

    $from = date("Y-m-d");
    $to = date("Y-m-d");
    $products = products::all();
    $profit = 0;
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

        $profit += $sales_amount - $purchase_amount;
        
    }

    $expenses = expenses::whereBetween('date', [$from, $to])->sum('amount');

    return $profit - $expenses;
}
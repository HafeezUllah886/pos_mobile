<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\expenses;
use App\Models\products;
use App\Models\purchase_details;
use App\Models\sale_details;
use App\Models\sales;
use Carbon\Carbon;
use Illuminate\Container\Attributes\DB;
use Illuminate\Http\Request;

class dashboardController extends Controller
{
    public function index()
    {

            /* $topProducts = products::withCount('saleDetails')
            ->withSum('saleDetails', 'price')
            ->orderByDesc('sale_details_count')
            ->take(5)
            ->get();

            $topProductsArray = [];

            foreach($topProducts as $product)
            {
                $stock = purchase_details::where('productID', $product->id)->where('status', 'Available')->count();
                $price = avgSalePrice('all', 'all', $product->id);

                $topProductsArray [] = ['name' => $product->name, 'price' => $price, 'stock' => $stock, 'amount' => $product->sale_details_sum_price, 'sold' => $product->sale_details_count];
            }

            /// Top Customers

            $topCustomers = accounts::where('type', 'Customer')
            ->withSum('sale', 'total')
            ->orderByDesc('sale_sum_total')
            ->take(5)
            ->get();

            $topCustomersArray = [];

            foreach($topCustomers as $customer)
            {
                if($customer->id != 2)
                {
                    $balance = getAccountBalance($customer->id);
                    $customer_purchases = $customer->sale_sum_total;

                    $topCustomersArray [] = ['name' => $customer->title, 'purchases' => $customer_purchases, 'balance' => $balance];
                }

            } */



        return view('dashboard.index');
    }
}

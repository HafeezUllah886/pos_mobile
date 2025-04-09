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

        $months = [];

        for ($i = 0; $i < 12; $i++) {
            $date = Carbon::now()->subMonths($i);

            $firstDay = $date->copy()->firstOfMonth()->toDateString();

            $lastDay = $date->copy()->lastOfMonth()->toDateString();

            $monthName = $date->format('M');

            $months[] = ['first' => $firstDay, 'last' => $lastDay, 'name' => $monthName];
        }

            $months = array_reverse($months);

            $sales = [];
            $monthNames = [];
            $expenses = [];
            $products = products::all();
            $profits = [];

            $last_sale = 0;
            $last_expense = 0;
            $last_profit = 0;
            foreach($months as $key => $month)
            {

                 $first = $month['first'];
                $last = $month['last'];
                $sale = sales::whereBetween('date', [$first, $last])->count();
                $expense = expenses::whereBetween('date', [$first, $last])->sum('amount');
                $sales[] = $sale;
                $expenses[] = $expense;
                $monthNames [] = $month['name'];
                $profit = 0;
                foreach($products as $product)
                {
                    $purchases = purchase_details::where('productID', $product->id)->whereBetween('date', [$first, $last]);
                    $purchases_amount = $purchases->sum('amount');
                    $purchases_qty = $purchases->sum('qty');
                    $purchase_qty = $purchases_qty;
                    if($purchase_qty > 0)
                    {
                        $purchase_price = $purchases_amount / $purchase_qty;
                    }
                    else
                    {
                        $purchase_price = 0;
                    }


                    $sales1 = sale_details::where('productID', $product->id)->whereBetween('date', [$first, $last]);
                    $sales_amount = $sales1->sum('amount');
                    $sales_qty = $sales1->sum('qty');

                    if($sales_qty > 0)
                    {
                        $sale_price = $sales_amount / $sales_qty;
                    }
                    else
                    {
                        $sale_price = 0;
                    }

                    $ppi = $sale_price - $purchase_price;
                    $ppp = $ppi * $sales_qty;
                    $profit += $ppp;
                }

                $profits[] = $profit - $expense;

                $last_sale = $sale;
                $last_expense = $expense;
                $last_profit = $profit;

            }


            /// Top five products

            $topProducts = products::withSum('saleDetails', 'qty')->withSum('saleDetails', 'amount')
            ->orderByDesc('sale_details_sum_qty')
            ->take(5)
            ->get();

            $topProductsArray = [];

            foreach($topProducts as $product)
            {
                $stock = getStock($product->id);
                $price = avgSalePrice('all', 'all', $product->id);

                $topProductsArray [] = ['name' => $product->name, 'price' => $price, 'stock' => $stock, 'amount' => $product->sale_details_sum_amount, 'sold' => $product->sale_details_sum_qty];
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

            }



        return view('dashboard.index', compact('sales', 'monthNames', 'expenses', 'profits', 'last_sale', 'last_expense', 'last_profit', 'topProductsArray', 'topCustomersArray'));
    }
}

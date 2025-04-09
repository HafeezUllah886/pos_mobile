<?php

namespace App\Http\Controllers;

use App\Models\products;
use Illuminate\Http\Request;

class ajaxController extends Controller
{
    public function searchProducts(Request $request)
    {
        $query = $request->get('q');
        // Search products by name (whether in English or Urdu)
        $products = products::where('name', 'LIKE', "%$query%")->get();
        dashboard();
        // Format the response for Selectize.js
        $formattedProducts = $products->map(function($product) {
            return [
                'value' => $product->id,  // Unique identifier
                'text' => $product->name  // Product name (English or Urdu)
            ];
        });

        return response()->json(['products' => $formattedProducts], 200, ['Content-Type' => 'application/json; charset=UTF-8']);
    }
}

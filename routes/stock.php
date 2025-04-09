<?php

use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {



    Route::get('products/stock/{id}/{warehouse}/{from}/{to}', [StockController::class, 'show'])->name('stockDetails');
    Route::resource('product_stock', StockController::class);

});


<?php

use App\Http\Controllers\ajaxController;

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::get("/search_products", [ajaxController::class, 'searchProducts']);

});

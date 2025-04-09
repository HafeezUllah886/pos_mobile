<?php

use App\Http\Controllers\QuotationController;
use App\Http\Middleware\confirmPassword;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::resource('quotation', QuotationController::class);

    Route::get("quotation/delete/{id}", [QuotationController::class, 'destroy'])->name('quotation.delete')->middleware(confirmPassword::class);
    Route::get("quotation/pdf/{id}", [QuotationController::class, 'pdf']);

});

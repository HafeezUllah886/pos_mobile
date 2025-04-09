<?php

use App\Http\Controllers\SalePaymentsController;
use App\Http\Controllers\SalesController;
use App\Http\Middleware\confirmPassword;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::resource('sale', SalesController::class);

    Route::get("sales/getproduct/{id}", [SalesController::class, 'getSignleProduct']);
    Route::get("sales/delete/{id}", [SalesController::class, 'destroy'])->name('sale.delete')->middleware(confirmPassword::class);
    Route::get("sales/pdf/{id}", [SalesController::class, 'pdf'])->name('sale.pdf');

    Route::get('salepayment/{id}', [SalePaymentsController::class, 'index'])->name('salePayment.index');
    Route::get('salepayment/show/{id}', [SalePaymentsController::class, 'show'])->name('salePayment.show');
    Route::get('salepayment/delete/{id}/{ref}', [SalePaymentsController::class, 'destroy'])->name('salePayment.delete')->middleware(confirmPassword::class);
    Route::resource('sale_payment', SalePaymentsController::class);

});

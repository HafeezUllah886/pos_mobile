<?php

use App\Http\Controllers\dailycashbookController;
use App\Http\Controllers\ledgerReportController;
use App\Http\Controllers\profitController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::get('/reports/profit', [profitController::class, 'index'])->name('reportProfit');
    Route::get('/reports/profit/{from}/{to}', [profitController::class, 'data'])->name('reportProfitData');

    Route::get('/reports/dailycashbook', [dailycashbookController::class, 'index'])->name('reportCashbook');
    Route::get('/reports/dailycashbook/{date}', [dailycashbookController::class, 'details'])->name('reportCashbookData');

    Route::get('/reports/ledger', [ledgerReportController::class, 'index'])->name('reportLedger');
    Route::get('/reports/ledger/{from}/{to}/{type}', [ledgerReportController::class, 'data'])->name('reportLedgerData');

});

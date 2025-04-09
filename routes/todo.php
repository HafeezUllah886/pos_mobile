<?php


use App\Http\Controllers\TodoController;
use App\Http\Middleware\confirmPassword;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::resource('/todos', TodoController::class);
    Route::get('/todo/delete/{id}', [TodoController::class, 'delete'])->name('todo.delete')->middleware(confirmPassword::class);
    Route::get('/todo/status/{id}', [TodoController::class, 'status'])->name('todo.status');

});

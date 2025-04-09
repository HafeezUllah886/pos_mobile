<?php

use App\Http\Controllers\authController;
use App\Http\Controllers\confirmPasswordController;
use App\Http\Controllers\profileController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [authController::class, 'index'])->name('login')->middleware("guest");
Route::post('/login', [authController::class, 'login'])->name('signin');

Route::middleware('auth')->group(function () {

    Route::get('/confirm-password', [confirmPasswordController::class, 'showConfirmPasswordForm'])->name('confirm-password');
    Route::post('/confirm-password', [confirmPasswordController::class, 'confirmPassword']);

    Route::get('/logout', [authController::class, 'logout'])->name('logout');
    Route::get('/profile', [profileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [profileController::class, 'update'])->name('updateProfile');
    Route::post('/profile/changepassword', [profileController::class, 'changePassword'])->name('changePassword');
});

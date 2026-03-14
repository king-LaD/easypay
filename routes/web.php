<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;

Route::get('/payment/{ref_externe}', [TransactionController::class, 'showCheckout'])->name('payment.checkout');
Route::post('/validation', [TransactionController::class, 'showRecap'])->name('payment.recap');
Route::post('/process', [TransactionController::class, 'initiatePayment'])->name('payment.process');
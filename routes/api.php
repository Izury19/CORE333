<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentApiController;

Route::prefix('dashboard/payments')->group(function () {
    Route::get('/', [PaymentApiController::class, 'index']);
    Route::get('/summary', [PaymentApiController::class, 'summary']);
    Route::put('/{id}/status', [PaymentApiController::class, 'updateStatus']);
    Route::post('/{id}/reminder', [PaymentApiController::class, 'sendReminder']);
});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentApiController;
use App\Http\Controllers\Api\MaintenanceScheduleApiController;
use App\Http\Controllers\ContractController;

/*
|--------------------------------------------------------------------------
| Payment API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('dashboard/payments')->group(function () {
    Route::get('/', [PaymentApiController::class, 'index']);
    Route::get('/summary', [PaymentApiController::class, 'summary']);
    Route::put('/{id}/status', [PaymentApiController::class, 'updateStatus']);
    Route::post('/{id}/reminder', [PaymentApiController::class, 'sendReminder']);
});

/*
|--------------------------------------------------------------------------
| Maintenance API Routes (v1)
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function () {
    // ✅ PROTECTED ROUTE WITH API KEY
    Route::post('/maintenance/schedules', [MaintenanceScheduleApiController::class, 'store'])
        ->middleware('api.key');
    
    // Public health check endpoint
    Route::get('/health', function () {
        return response()->json([
            'status' => 'healthy',
            'timestamp' => now()->toISOString(),
            'service' => 'Maintenance API',
            'version' => '1.0'
        ]);
    });
});

/*
|--------------------------------------------------------------------------
| Contract Management API Routes
|--------------------------------------------------------------------------
*/
// Contract status updates from Legal Management Module
Route::post('/contract-status', [ContractController::class, 'updateContractStatus'])
    ->name('api.contract.status');
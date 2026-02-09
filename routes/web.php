<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\PaymentApiController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\OTPController;
use App\Http\Controllers\ReportingController;
use App\Http\Controllers\BillingInvoiceController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'main'])->name('landing');

Route::view('/sendotp', 'auth.otp');
Route::view('/success', 'auth.success')->name('auth.success');

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);


/*
|--------------------------------------------------------------------------
| Dashboard & User
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [ReportingController::class, 'dashboard'])->name('dashboard');
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::get('/settings', [SettingsController::class, 'index'])->name('settings');


/*
|--------------------------------------------------------------------------
| Billing & Invoicing
|--------------------------------------------------------------------------
*/
Route::prefix('billing/invoices')->name('billing.invoices.')->group(function () {
    Route::get('/', [BillingInvoiceController::class, 'index'])->name('index');
    Route::post('/demo-store', [BillingInvoiceController::class, 'demoStore'])->name('demo-store');
    Route::post('/scan', [BillingInvoiceController::class, 'scanDuplicates'])->name('scan');
    Route::get('/{id}/pdf', [BillingInvoiceController::class, 'downloadPdf'])->name('pdf');
    Route::get('/{id}', [BillingInvoiceController::class, 'show'])->name('show');
    Route::post('/bulk-forward', [BillingInvoiceController::class, 'bulkForwardToFinancials'])->name('bulk-forward');
});


/*
|--------------------------------------------------------------------------
| Record & Payment Management
|--------------------------------------------------------------------------
*/
Route::get('/record-payment', [RecordController::class, 'index'])->name('record.index');
Route::post('/record-payment', [RecordController::class, 'store'])->name('record.store');


/*
|--------------------------------------------------------------------------
| Schedule Preventive Maintenance
|--------------------------------------------------------------------------
*/
Route::get('/maintenance-sched', [MaintenanceController::class, 'index'])->name('maintenance-sched');
Route::get('/maintenance-history', [MaintenanceController::class, 'showHistoryLog'])->name('maintenance-history');
Route::get('/maintenance-dashboard', [MaintenanceController::class, 'dashboard'])->name('maintenance-dashboard');
Route::get('/assign-tech', [TechnicianController::class, 'index'])->name('assign-tech');
Route::post('/technicians/{technician}/upload-image', [TechnicianController::class, 'uploadImage'])->name('technicians.uploadImage');
Route::post('/maintenance/{id}/upload-proof', [MaintenanceController::class, 'markCompleted'])->name('maintenance.complete');
Route::post('/send-email-notification', [MaintenanceController::class, 'sendEmailNotification']);


/*
|--------------------------------------------------------------------------
| Contract Management
|--------------------------------------------------------------------------
*/
Route::get('/contract-management', [ContractController::class, 'index'])->name('contract.management');
Route::post('/contract-management', [ContractController::class, 'store'])->name('contracts.store');

// Contract Actions
Route::get('/contracts/{id}', [ContractController::class, 'show'])->name('contracts.show');
Route::get('/contracts/{id}/view', [ContractController::class, 'view'])->name('contracts.view');
Route::get('/contracts/{id}/edit', [ContractController::class, 'edit'])->name('contracts.edit');
Route::put('/contracts/{id}', [ContractController::class, 'update'])->name('contracts.update');
Route::delete('/contracts/{id}', [ContractController::class, 'destroy'])->name('contracts.destroy');
Route::post('/contracts/{id}/refresh-status', [ContractController::class, 'refreshStatus'])->name('contracts.refresh-status');


/*
|--------------------------------------------------------------------------
| Permit Management
|--------------------------------------------------------------------------
*/
Route::get('/manage-permits', [ContractController::class, 'permitsIndex'])->name('manage-permits');
Route::post('/permits', [ContractController::class, 'storePermit'])->name('permits.store');
Route::get('/permits/{id}/view', [ContractController::class, 'viewPermit'])->name('permits.view');
Route::get('/permits/{id}/edit', [ContractController::class, 'editPermit'])->name('permits.edit');
Route::put('/permits/{id}', [ContractController::class, 'updatePermit'])->name('permits.update');
Route::delete('/permits/{id}', [ContractController::class, 'destroyPermit'])->name('permits.destroy');


/*
|--------------------------------------------------------------------------
| Reporting & Analytics
|--------------------------------------------------------------------------
*/
Route::get('/financial-report', [ReportingController::class, 'index'])->name('financial-report');
Route::get('/financial-report/export/excel', [ReportingController::class, 'exportExcel'])->name('financial-report.export.excel');
Route::get('/financial-report/export/pdf', [ReportingController::class, 'exportPdf'])->name('financial-report.export.pdf');


/*
|--------------------------------------------------------------------------
| Delivery & Receipts
|--------------------------------------------------------------------------
*/
Route::get('/delivery', [InvoiceController::class, 'delivery'])->name('delivery');
Route::post('/invoices/{id}/generate-receipt', [RecordController::class, 'generateReceipt'])->name('invoices.generateReceipt');
Route::resource('receipts', ReceiptController::class);


/*
|--------------------------------------------------------------------------
| Maintenance API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('maintenance')->name('maintenance.')->group(function () {
    Route::get('/', [MaintenanceController::class, 'index'])->name('index');
    Route::get('/create', [MaintenanceController::class, 'create'])->name('create');
    Route::post('/store', [MaintenanceController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [MaintenanceController::class, 'edit'])->name('edit');
    Route::put('/{id}', [MaintenanceController::class, 'update'])->name('update');
    Route::delete('/{id}', [MaintenanceController::class, 'destroy'])->name('destroy');
});


/*
|--------------------------------------------------------------------------
| Payments & Jobs
|--------------------------------------------------------------------------
*/
Route::prefix('dashboard/payments')->as('dashboard.payments.')->group(function () {
    Route::get('/', [PaymentApiController::class, 'index'])->name('index');
    Route::get('/summary', [PaymentApiController::class, 'summary'])->name('summary');
    Route::put('/{id}/status', [PaymentApiController::class, 'updateStatus'])->name('updateStatus');
    Route::post('/{id}/reminder', [PaymentApiController::class, 'sendReminder'])->name('sendReminder');
});

Route::get('/dashboard/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::put('/dashboard/jobs/{job}/status', [JobController::class, 'updateStatus'])->name('jobs.updateStatus');


/*
|--------------------------------------------------------------------------
| Forgot Password & OTP
|--------------------------------------------------------------------------
*/
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/verify-otp', [OTPController::class, 'showVerifyForm'])->name('otp.verify.form');
Route::post('/verify-otp', [OTPController::class, 'verify'])->name('otp.verify.submit');
Route::get('/resend-otp', [OTPController::class, 'resend'])->name('otp.resend');


/*
|--------------------------------------------------------------------------
| Test Routes
|--------------------------------------------------------------------------
*/
Route::get('/test-db', function() {
    try {
        $records = \DB::table('records')->count();
        return "Connected! Found {$records} records.";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/auto-logout', function () {
    auth()->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/');
})->name('auto.logout');

// Test route to manually create invoice + record
Route::get('/test-invoice-record', function () {
    // Create invoice
    $invoice = \App\Models\BillingInvoice::create([
        'invoice_uid' => 'INV-TEST-' . time(),
        'client_name' => 'Test Client',
        'equipment_type' => 'mobile_crane',
        'equipment_id' => 'MOB-9999',
        'hours_used' => 8,
        'hourly_rate' => 2500,
        'total_amount' => 22400,
        'billing_period_start' => now()->subDays(2),
        'billing_period_end' => now(),
        'status' => 'issued'
    ]);

    // Insert record (bypass all constraints)
    \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    \DB::table('records')->insert([
        'invoice_id' => $invoice->id,
        'payment_uid' => 'PAY-TEST-' . $invoice->id,
        'payment_method' => 'pending',
        'reference_number' => $invoice->invoice_uid,
        'status' => 'pending',
        'total' => 0,
        'client_name' => $invoice->client_name,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    return "✅ Invoice ID: {$invoice->id} | Record created!";
});
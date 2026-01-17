<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\InvoiceController; // <-- Existing controller (we'll use it for billing_invoices)
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
Route::get('/dashboard', [AuthController::class, 'showMainPage'])->name('dashboard');
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::get('/settings', [SettingsController::class, 'index'])->name('settings');

/*
/*
|--------------------------------------------------------------------------
| Billing & Invoicing (Cleaned)
|--------------------------------------------------------------------------
*/
// Main billing invoices route (MISSING!)
Route::get('/billing-invoices', [BillingInvoiceController::class, 'index'])
    ->name('billing.invoices.index');

// Demo invoice generation
Route::post('/billing-invoices/demo', [BillingInvoiceController::class, 'demoStore'])
    ->name('billing.invoices.demo-store');

// Scan duplicates
Route::post('/billing-invoices/scan', [BillingInvoiceController::class, 'scanDuplicates'])
    ->name('billing.invoices.scan');

// PDF download
Route::get('/billing-invoices/{id}/pdf', [BillingInvoiceController::class, 'downloadPdf'])
    ->name('billing.invoices.pdf');

// Show single invoice
Route::get('/billing-invoices/{id}', [BillingInvoiceController::class, 'show'])
    ->name('billing.invoices.show');

// InvoiceController routes (existing)
Route::resource('invoices', InvoiceController::class)->except(['create', 'edit']);
Route::patch('/invoices/{id}/status', [InvoiceController::class, 'updateStatus'])->name('invoices.update.status');
Route::delete('/invoices/{id}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');

    
/*
|--------------------------------------------------------------------------
| Record & Payment Management
|--------------------------------------------------------------------------
*/


// Record & Payment Routes
Route::get('/record-payment', [RecordController::class, 'index'])->name('record.index');
Route::post('/record-payment', [RecordController::class, 'store'])->name('record.store');

/*
|--------------------------------------------------------------------------
| Schedule Preventive (Views Only)
|--------------------------------------------------------------------------
*/
Route::view('/maintenance-notif', 'SchedulePreventive.maintenance-notif')->name('maintenance-notif');
Route::get('/maintenance-history', [MaintenanceController::class, 'showHistoryLog'])->name('maintenance-history');
Route::get('/assign-tech', [TechnicianController::class, 'index'])->name('assign-tech');
Route::post('/technicians/{technician}/upload-image', [TechnicianController::class, 'uploadImage'])
     ->name('technicians.uploadImage');

/*
|--------------------------------------------------------------------------
| Contract & Permit (Views Only)
|--------------------------------------------------------------------------
*/
// Contract Management Routes
Route::get('/contract-management', [ContractController::class, 'index'])->name('contract.management');
// Dapat nasa taas ng file: use statement

// Contract Management Routes
Route::get('/contract-management', [ContractController::class, 'index'])->name('contract.management');
Route::post('/contract-management', [ContractController::class, 'store'])->name('contracts.store');
Route::get('/contract-management/{id}', [ContractController::class, 'show'])->name('contracts.show'); // ✅ ADD THIS
Route::get('/contract-management/{id}/pdf', [ContractController::class, 'exportPdf'])->name('contracts.pdf');


/*
|--------------------------------------------------------------------------
| Reporting & Analytics (Views Only)
|--------------------------------------------------------------------------
*/
Route::view('/financial-report', 'Reporting and Analytics.financial-report')->name('financial-report');
Route::get('/reporting-analytics', [ReportingController::class, 'index'])->name('reporting.analytics');


/*
|--------------------------------------------------------------------------
| Delivery & Receipts
|--------------------------------------------------------------------------
*/
Route::get('/delivery', [InvoiceController::class, 'delivery'])->name('delivery');
Route::post('/invoices/{id}/generate-receipt', [RecordController::class, 'generateReceipt'])
    ->name('invoices.generateReceipt');
Route::resource('receipts', ReceiptController::class);

/*
|--------------------------------------------------------------------------
| Maintenance
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
Route::get('/maintenance-sched', [MaintenanceController::class, 'index'])->name('maintenance-sched');
Route::get('/calendar/events', [MaintenanceController::class, 'calendarEvents'])->name('calendar.events');

/*
|--------------------------------------------------------------------------
| Contract
|--------------------------------------------------------------------------
*/
Route::post('/make-contract', [ContractController::class, 'store'])->name('contracts.store');

/*
|--------------------------------------------------------------------------
| Payments
|--------------------------------------------------------------------------
*/

Route::prefix('dashboard/payments')->as('dashboard.payments.')->group(function () {
    Route::get('/', [PaymentApiController::class, 'index'])->name('index');
    Route::get('/summary', [PaymentApiController::class, 'summary'])->name('summary');
    Route::put('/{id}/status', [PaymentApiController::class, 'updateStatus'])->name('updateStatus');
    Route::post('/{id}/reminder', [PaymentApiController::class, 'sendReminder'])->name('sendReminder');
});

Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/payments', [PaymentApiController::class, 'fetchInvoices'])->name('payments.fetch');
    Route::put('/payments/{id}/status', [PaymentApiController::class, 'updateStatus'])->name('payments.updateStatus');
    Route::post('/payments/{id}/reminder', [PaymentApiController::class, 'sendReminder'])->name('payments.sendReminder');
});

Route::get('/dashboard/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::put('/dashboard/jobs/{job}/status', [JobController::class, 'updateStatus'])->name('jobs.updateStatus');

Route::post('/send-email-notification', [MaintenanceController::class, 'sendEmailNotification']);
Route::post('/maintenance/{id}/upload-proof', [MaintenanceController::class, 'markCompleted'])->name('maintenance.complete');

Route::get('/billing/record', [InvoiceController::class, 'record'])->name('billing.record');
Route::get('/billing/record/{id}', [InvoiceController::class, 'show'])->name('billing.show');

Route::resource('technicians', TechnicianController::class);

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
| AI-Driven Billing & Invoicing (New System)
|--------------------------------------------------------------------------
*/
Route::prefix('billing-invoices')->name('billing.invoices.')->middleware('auth')->group(function () {
    Route::get('/', [BillingInvoiceController::class, 'index'])->name('index');
    Route::get('/create', [BillingInvoiceController::class, 'create'])->name('create');
    Route::post('/', [BillingInvoiceController::class, 'store'])->name('store');
    Route::get('/{id}', [BillingInvoiceController::class, 'show'])->name('show');
});




Route::get('/test-db', function() {
    try {
        $records = \DB::table('records')->count();
        return "Connected! Found {$records} records.";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});



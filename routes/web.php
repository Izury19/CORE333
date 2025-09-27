<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\PaymentApiController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\TechnicianController;

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
|--------------------------------------------------------------------------
| Billing & Invoicing (Views Only)
|--------------------------------------------------------------------------
*/
Route::get('/order', [JobController::class, 'index'])->name('order');
Route::view('/invoice', 'Billing and Invoicing.invoice')->name('invoice');
Route::get('/record', [RecordController::class, 'record'])->name('record');


/*
|--------------------------------------------------------------------------
| Record & Payment (Views Only)
|--------------------------------------------------------------------------
*/
Route::view('/invoice-tracking', 'Record And Payment.invoice-tracking')->name('invoice-tracking');
Route::get('/manage-payment', [PaymentController::class, 'index'])->name('manage-payment');
Route::view('/ledger-viewer', 'Record And Payment.ledger-viewer')->name('ledger-viewer');
Route::view('/payment-reminders', 'Record And Payment.payment-reminders')->name('payment-reminders');

// Upload proof of payment (client side)
Route::get('/payments/{invoiceId}/upload', [PaymentController::class, 'create'])
    ->name('payments.upload'); // show form

Route::post('/payments/{invoiceId}', [PaymentController::class, 'store'])
    ->name('payments.upload.store'); // handle form submission

// Payment approval/rejection (admin side)
Route::patch('/payments/{id}/approve', [PaymentController::class, 'markApproved'])
    ->name('payments.approve');

Route::patch('/payments/{id}/reject', [PaymentController::class, 'markRejected'])
    ->name('payments.reject');
Route::patch('/payments/{id}/cancel', [PaymentController::class, 'markCancelled'])
    ->name('payments.cancel');

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
Route::view('/make-contract', 'Contract and Permit.make-contract')->name('make-contract');
Route::view('/manage-permits', 'Contract and Permit.manage-permits')->name('manage-permits');
Route::view('/renewal-req', 'Contract and Permit.renewal-req')->name('renewal-req');
Route::view('/expiry-notif', 'Contract and Permit.expiry-notif')->name('expiry-notif');

/*
|--------------------------------------------------------------------------
| Reporting & Analytics (Views Only)
|--------------------------------------------------------------------------
*/
Route::view('/financial-report', 'Reporting and Analytics.financial-report')->name('financial-report');
Route::view('/maintenance-report', 'Reporting and Analytics.maintenance-report')->name('maintenance-report');
Route::view('/contractpermit-report', 'Reporting and Analytics.contractpermit-report')->name('contractpermit-report');
Route::view('/ai-report', 'Reporting and Analytics.ai-report')->name('ai-report');

/*
|--------------------------------------------------------------------------
| Invoice & Delivery
|--------------------------------------------------------------------------
*/
Route::get('/invoice/create', [InvoiceController::class, 'create'])->name('invoice.create');
Route::post('/invoice/store', [InvoiceController::class, 'store'])->name('invoice.store');
Route::get('/delivery', [InvoiceController::class, 'delivery'])->name('delivery');

Route::resource('invoices', InvoiceController::class);

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
Route::resource('payments', PaymentController::class);
Route::post('payments/{id}/mark-paid', [PaymentController::class, 'markPaid'])->name('payments.markPaid');


// Payment routes

Route::prefix('dashboard/payments')->as('dashboard.payments.')->group(function () {
    Route::get('/', [PaymentApiController::class, 'index'])->name('index');
    Route::get('/summary', [PaymentApiController::class, 'summary'])->name('summary');
    Route::put('/{id}/status', [PaymentApiController::class, 'updateStatus'])->name('updateStatus');
    Route::post('/{id}/reminder', [PaymentApiController::class, 'sendReminder'])->name('sendReminder');
});
/*
|--------------------------------------------------------------------------
| Dashboard - Payment Management (AJAX)
|--------------------------------------------------------------------------
*/
Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/payments', [PaymentApiController::class, 'fetchInvoices'])->name('payments.fetch');
    Route::put('/payments/{id}/status', [PaymentApiController::class, 'updateStatus'])->name('payments.updateStatus');
    Route::post('/payments/{id}/reminder', [PaymentApiController::class, 'sendReminder'])->name('payments.sendReminder');
});

Route::get('/dashboard/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::put('/dashboard/jobs/{job}/status', [JobController::class, 'updateStatus'])->name('jobs.updateStatus');

Route::post('/send-email-notification', [App\Http\Controllers\MaintenanceController::class, 'sendEmailNotification']);

Route::post('/maintenance/{id}/upload-proof', [MaintenanceController::class, 'markCompleted'])->name('maintenance.complete');

Route::get('/billing/record', [InvoiceController::class, 'record'])->name('billing.record');
Route::get('/billing/record/{id}', [InvoiceController::class, 'show'])->name('billing.show');

Route::post('/invoices/{id}/generate-receipt', [RecordController::class, 'generateReceipt'])
    ->name('invoices.generateReceipt');

Route::get('/record', [RecordController::class, 'index'])->name('record');

Route::resource('receipts', ReceiptController::class);

Route::resource('technicians', TechnicianController::class);


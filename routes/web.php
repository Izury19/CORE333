<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Carbon;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\PaymentController;

Route::get('/', [LandingController::class, 'main'])->name('landing');

Route::get('/sendotp', function () {
    // You can load a view or redirect elsewhere
    return view('auth.otp');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('/dashboard', [AuthController::class, 'showMainPage'])->name('dashboard');

// Profile route
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

// Settings route
Route::get('/settings', [SettingsController::class, 'index'])->name('settings');

// Logout (must be POST)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// For Successfully registered users
Route::get('/success', function () {
    return view('auth.success');
})->name('auth.success');

/*
// Commented out duplicate dashboard route
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
*/

Route::get('/order', function () {
    return view('Billing and Invoicing.order');
})->name('order');

Route::get('/invoice', function () {
    return view('Billing and Invoicing.invoice');
})->name('invoice');

/*
// Commented out duplicate delivery route (closure)
Route::get('/delivery', function () {
    return view('Billing and Invoicing.delivery');
})->name('delivery');
*/

//Route::get('/payment', function () {
//   return view('Billing and Invoicing.payment');
//})->name('payment');

Route::get('/record', function () {
    return view('Billing and Invoicing.record');
})->name('record');

Route::get('/invoice-tracking', function () {
    return view('Record and Payment.invoice-tracking');
})->name('invoice-tracking');

Route::get('/manage-payment', function () {
    return view('Record and Payment.manage-payment');
})->name('manage-payment');

Route::get('/ledger-viewer', function () {
    return view('Record and Payment.ledger-viewer');
})->name('ledger-viewer');

Route::get('/payment-reminders', function () {
    return view('Record and Payment.payment-reminders');
})->name('payment-reminders');

Route::get('/maintenance-notif', function () {
    return view('SchedulePreventive.maintenance-notif');
})->name('maintenance-notif');

Route::get('/maintenance-history', function () {
    return view('SchedulePreventive.maintenance-history');
})->name('maintenance-history');

Route::get('/assign-tech', function () {
    return view('SchedulePreventive.assign-tech');
})->name('assign-tech');


// Commented out duplicate make-contract closure route
Route::get('/make-contract', function () {
    return view('Contract and Permit.make-contract');
})->name('make-contract');


Route::get('/manage-permits', function () {
    return view('Contract and Permit.manage-permits');
})->name('manage-permits');

Route::get('/renewal-req', function () {
    return view('Contract and Permit.renewal-req');
})->name('renewal-req');

Route::get('/expiry-notif', function () {
    return view('Contract and Permit.expiry-notif');
})->name('expiry-notif');

Route::get('/financial-report', function () {
    return view('Reporting and Analytics.financial-report');
})->name('financial-report');

Route::get('/maintenance-report', function () {
    return view('Reporting and Analytics.maintenance-report');
})->name('maintenance-report');

Route::get('/contractpermit-report', function () {
    return view('Reporting and Analytics.contractpermit-report');
})->name('contractpermit-report');

Route::get('/ai-report', function () {
    return view('Reporting and Analytics.ai-report');
})->name('ai-report');

Route::get('/invoice/create', [InvoiceController::class, 'create'])->name('invoice.create');
Route::post('/invoice/store', [InvoiceController::class, 'store'])->name('invoice.store');

Route::get('/delivery', [InvoiceController::class, 'delivery'])->name('delivery');

Route::prefix('maintenance')->name('maintenance.')->group(function () {
    Route::get('/', [MaintenanceController::class, 'index'])->name('index');
    Route::get('/create', [MaintenanceController::class, 'create'])->name('create');
    Route::post('/store', [MaintenanceController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [MaintenanceController::class, 'edit'])->name('edit');
    Route::put('/{id}', [MaintenanceController::class, 'update'])->name('update');
    Route::delete('/{id}', [MaintenanceController::class, 'destroy'])->name('destroy');
});

Route::get('/maintenance-sched', [MaintenanceController::class, 'index'])->name('maintenance-sched');

Route::get('/make-contract', [ContractController::class, 'create'])->name('make-contract');
Route::post('/make-contract', [ContractController::class, 'store'])->name('contracts.store');

// For FullCalendar to fetch the maintenance schedules as events
Route::get('/calendar/events', [MaintenanceController::class, 'calendarEvents'])->name('calendar.events');

Route::get('/maintenance/{id}/edit', [MaintenanceController::class, 'edit'])->name('maintenance.edit');
Route::resource('invoices', InvoiceController::class);

Route::resource('payments', PaymentController::class);
Route::post('payments/{id}/mark-paid', [PaymentController::class, 'markPaid'])->name('payments.markPaid');
Route::get('/payment', [PaymentController::class, 'index'])->name('payment');
Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');

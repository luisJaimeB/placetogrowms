<?php

use App\Http\Controllers\Admin\AclController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceImportController;
use App\Http\Controllers\MicrositeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SetLocaleController;
use App\Http\Controllers\SuscriptionController;
use App\Http\Controllers\Suscriptor\SuscriptionController as SuscriptionSuscriptorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

Route::get('/dashboard', [DashboardController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/microsites', [MicrositeController::class, 'index'])->middleware('can:microsites.index')->name('microsites.index');
    Route::get('/microsites/create', [MicrositeController::class, 'create'])->middleware('can:microsites.create')->name('microsites.create');
    Route::get('/microsites/{microsite}/edit', [MicrositeController::class, 'edit'])->middleware('can:microsites.update')->name('microsites.edit');
    Route::post('/microsites', [MicrositeController::class, 'store'])->middleware('can:microsites.create')->name('microsites.store');
    Route::patch('/microsites/{microsite}', [MicrositeController::class, 'update'])->middleware('can:microsites.update')->name('microsites.update');
    Route::get('/microsites/{microsite}', [MicrositeController::class, 'show'])->middleware('can:microsites.show')->name('microsites.show');
    Route::delete('/microsites/{microsite}', [MicrositeController::class, 'destroy'])->middleware('can:microsites.delete')->name('microsites.destroy');

    Route::get('/planes', [SuscriptionController::class, 'index'])->middleware('can:planes.index')->name('planes.index');
    Route::get('/planes/create', [SuscriptionController::class, 'create'])->middleware('can:planes.create')->name('planes.create');
    Route::get('/planes/{plan}/edit', [SuscriptionController::class, 'edit'])->middleware('can:planes.update')->name('planes.edit');
    Route::post('/planes', [SuscriptionController::class, 'store'])->middleware('can:planes.create')->name('planes.store');
    Route::patch('/planes/{plan}', [SuscriptionController::class, 'update'])->middleware('can:planes.update')->name('planes.update');
    Route::delete('/planes/{plan}', [SuscriptionController::class, 'destroy'])->middleware('can:planes.delete')->name('planes.destroy');

    Route::get('/subscriptions', [SuscriptionSuscriptorController::class, 'index'])->middleware('can:subscriptions.index')->name('subscriptions.index');
    Route::delete('/subscriptions/{subscription}', [SuscriptionSuscriptorController::class, 'destroy'])->middleware('can:subscriptions.delete')->name('subscriptions.destroy');

    Route::get('/invoices', [InvoiceController::class, 'index'])->middleware('can:invoices.index')->name('invoices.index');
    Route::get('/invoices/create', [InvoiceController::class, 'create'])->middleware('can:invoices.create')->name('invoices.create');
    Route::get('/invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->middleware('can:invoices.update')->name('invoices.edit');
    Route::post('/invoices', [InvoiceController::class, 'store'])->middleware('can:invoices.create')->name('invoices.store');
    Route::patch('/invoices/{invoice}', [InvoiceController::class, 'update'])->middleware('can:invoices.update')->name('invoices.update');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->middleware('can:invoices.show')->name('invoices.show');
    Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy'])->middleware('can:invoices.delete')->name('invoices.destroy');

    Route::get('/payments', [PaymentController::class, 'index'])->middleware('can:payment.index')->name('payments.index');
    Route::get('/payment/detail/{payment}', [PaymentController::class, 'paymentDetail'])->name('payment.details');

    Route::post('/import-invoices', [InvoiceImportController::class, 'import'])->name('import.invoices');
    Route::get('/imports', [InvoiceImportController::class, 'index'])->middleware('can:imports.index')->name('imports.index');
    Route::get('/imports/create', [InvoiceImportController::class, 'create'])->middleware('can:imports.create')->name('imports.create');

    Route::post('/permissions/update', [AclController::class, 'updatePermission']);
});

Route::get('/lang/{locale}', [SetLocaleController::class, 'setLang'])->name('setLang');

Route::resource('payments', PaymentController::class);
Route::get('payments/create/{microsite}', [PaymentController::class, 'create'])->name('payments.create');
Route::post('/process-payment', [PaymentController::class, 'processPayment'])->name('payments.process');
Route::get('/payments/return/{payment}', [PaymentController::class, 'handleReturn'])->name('payments.return');
Route::get('/payments/show/{payment}', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/payments/invoice/search', [PaymentController::class, 'invoiceSearch'])->name('payment.invoice.search');
Route::get('/payments/selected-invoice-payment/{invoice}', [PaymentController::class, 'invoiceIndex'])->name('payment.invoice.index');

require __DIR__.'/auth.php';

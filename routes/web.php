<?php

use App\Http\Controllers\Admin\AclController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
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

    Route::get('/admin/users', [UserController::class, 'index'])->middleware('can:users.index')->name('users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->middleware('can:users.create')->name('users.create');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->middleware('can:users.update')->name('users.edit');
    Route::post('/admin/users', [UserController::class, 'store'])->middleware('can:users.create')->name('users.store');
    Route::patch('/admin/users/{user}', [UserController::class, 'update'])->middleware('can:users.update')->name('users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->middleware('can:users.delete')->name('users.destroy');

    Route::get('/admin/roles', [RoleController::class, 'index'])->middleware('can:roles.index')->name('roles.index');
    Route::get('/admin/roles/create', [RoleController::class, 'create'])->middleware('can:roles.create')->name('roles.create');
    Route::get('/admin/roles/{role}/edit', [RoleController::class, 'edit'])->middleware('can:roles.update')->name('roles.edit');
    Route::post('/admin/roles', [RoleController::class, 'store'])->middleware('can:roles.create')->name('roles.store');
    Route::patch('/admin/roles/{role}', [RoleController::class, 'update'])->middleware('can:roles.update')->name('roles.update');
    Route::delete('/admin/roles/{role}', [RoleController::class, 'destroy'])->middleware('can:roles.delete')->name('roles.destroy');

    Route::get('/admin/permissions', [PermissionController::class, 'index'])->middleware('can:permissions.index')->name('permissions.index');
    Route::get('/admin/permissions/create', [PermissionController::class, 'create'])->middleware('can:permissions.create')->name('permissions.create');
    Route::get('/admin/permissions/{permission}/edit', [PermissionController::class, 'edit'])->middleware('can:permissions.update')->name('permissions.edit');
    Route::post('/admin/permissions', [PermissionController::class, 'store'])->middleware('can:permissions.create')->name('permissions.store');
    Route::patch('/admin/permissions/{permission}', [PermissionController::class, 'update'])->middleware('can:permissions.update')->name('permissions.update');
    Route::delete('/admin/permissions/{permission}', [PermissionController::class, 'destroy'])->middleware('can:permissions.delete')->name('permissions.destroy');

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

    Route::get('/admin/acls', [AclController::class, 'index'])->middleware('can:acls.index')->name('acls.index');
    Route::get('/admin/acls/create', [AclController::class, 'create'])->middleware('can:acls.create')->name('acls.create');
    Route::get('/admin/acls/{acl}/edit', [AclController::class, 'edit'])->middleware('can:acls.update')->name('acls.edit');
    Route::post('/admin/acls', [AclController::class, 'store'])->middleware('can:acls.create')->name('acls.store');
    Route::patch('/admin/acls/{acl}', [AclController::class, 'update'])->middleware('can:acls.update')->name('acls.update');
    Route::delete('/admin/acls/{acl}', [AclController::class, 'destroy'])->middleware('can:acls.delete')->name('acls.destroy');

    Route::get('/payment/detail/{payment}', [PaymentController::class, 'paymentDetail'])->name('payment.details');

    Route::post('/permissions/update', [AclController::class, 'updatePermission']);
});

Route::get('/lang/{locale}', [SetLocaleController::class, 'setLang'])->name('setLang');

Route::resource('payments', PaymentController::class);
Route::get('payments/create/{microsite}', [PaymentController::class, 'create'])->name('payments.create');
Route::post('/process-payment', [PaymentController::class, 'processPayment'])->name('payments.process');
Route::get('/payments/return/{payment}', [PaymentController::class, 'handleReturn'])->name('payments.return');
Route::get('/payments/show/{payment}', [PaymentController::class, 'show'])->name('payment.show');

require __DIR__.'/auth.php';

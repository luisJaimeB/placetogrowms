<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index']);

Route::get('/dashboard', [DashboardController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/admin/users', [UserController::class, 'index'])->middleware('can:users.index')->name('users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->middleware('can:users.create')->name('users.create');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->middleware('can:users.update')->name('users.edit');
    Route::post('/admin/users', [UserController::class, 'store'])->name('users.store');
    Route::patch('/admin/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->middleware('can:users.delete')->name('users.destroy');

    Route::get('/admin/roles', [RoleController::class, 'index'])->middleware('can:roles.index')->name('roles.index');
    Route::get('/admin/roles/create', [RoleController::class, 'create'])->middleware('can:roles.create')->name('roles.create');
    Route::get('/admin/roles/{role}/edit', [RoleController::class, 'edit'])->middleware('can:roles.update')->name('roles.edit');
    Route::post('/admin/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::patch('admin/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/admin/roles/{role}', [RoleController::class, 'destroy'])->middleware('can:roles.delete')->name('roles.destroy');

});

require __DIR__.'/auth.php';

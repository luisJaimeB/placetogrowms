<?php

use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->prefix('admin')->name('users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->prefix('admin')->name('users.create');

});

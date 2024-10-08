<?php

use App\Http\Controllers\Admin\AclController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->middleware('can:users.update')->name('users.edit');
    Route::post('/users', [UserController::class, 'store'])->middleware('can:users.create')->name('users.store');
    Route::patch('/users/{user}', [UserController::class, 'update'])->middleware('can:users.update')->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware('can:users.delete')->name('users.destroy');

    Route::get('/roles', [RoleController::class, 'index'])->middleware('can:roles.index')->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->middleware('can:roles.create')->name('roles.create');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->middleware('can:roles.update')->name('roles.edit');
    Route::post('/roles', [RoleController::class, 'store'])->middleware('can:roles.create')->name('roles.store');
    Route::patch('/roles/{role}', [RoleController::class, 'update'])->middleware('can:roles.update')->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->middleware('can:roles.delete')->name('roles.destroy');

    Route::get('/permissions', [PermissionController::class, 'index'])->middleware('can:permissions.index')->name('permissions.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->middleware('can:permissions.create')->name('permissions.create');
    Route::get('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->middleware('can:permissions.update')->name('permissions.edit');
    Route::post('/permissions', [PermissionController::class, 'store'])->middleware('can:permissions.create')->name('permissions.store');
    Route::patch('/permissions/{permission}', [PermissionController::class, 'update'])->middleware('can:permissions.update')->name('permissions.update');
    Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->middleware('can:permissions.delete')->name('permissions.destroy');

    Route::get('/acls', [AclController::class, 'index'])->middleware('can:acls.index')->name('acls.index');
    Route::get('/acls/create', [AclController::class, 'create'])->middleware('can:acls.create')->name('acls.create');
    Route::get('/acls/{acl}/edit', [AclController::class, 'edit'])->middleware('can:acls.update')->name('acls.edit');
    Route::post('/acls', [AclController::class, 'store'])->middleware('can:acls.create')->name('acls.store');
    Route::patch('/acls/{acl}', [AclController::class, 'update'])->middleware('can:acls.update')->name('acls.update');
    Route::delete('/acls/{acl}', [AclController::class, 'destroy'])->middleware('can:acls.delete')->name('acls.destroy');

});

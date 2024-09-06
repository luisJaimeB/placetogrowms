<?php

use App\Http\Controllers\Api\Admin\AclModelController;

Route::post('/acl-model', [AclModelController::class, 'index'])->name('index.aclmodel');

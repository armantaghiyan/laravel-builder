<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:admin')->group(function () {
    Route::controller(\App\Http\Controllers\User\Admin\AdminController::class)->group(function () {
    });
});

Route::controller(\App\Http\Controllers\User\Admin\AdminController::class)->group(function () {
    Route::get('admin/start', 'adminStart');
    Route::post('admin/login', 'login');
    Route::post('admin/logout', 'logout');
});

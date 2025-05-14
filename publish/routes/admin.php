<?php

use App\Http\Controllers\User\Admin\AdminController;
use Illuminate\Support\Facades\Route;

Route::controller(\App\Http\Controllers\User\Admin\AdminController::class)->group(function () {
	Route::get('admin/start', 'adminStart');
	Route::post('admin/login', 'login');
	Route::post('admin/logout', 'logout');
});

Route::middleware('auth:admin')->group(function () {
	Route::resource('admin', AdminController::class);
});

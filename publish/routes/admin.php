<?php

use App\Http\Controllers\App\AccessController;
use App\Http\Controllers\User\AdminController;
use Illuminate\Support\Facades\Route;

Route::controller(\App\Http\Controllers\App\AccessController::class)->group(function () {
	Route::post('access/role-toggle', 'roleToggle');
	Route::post('access/permission-toggle', 'permissionToggle');
	Route::resource('access', AccessController::class);
});

Route::controller(\App\Http\Controllers\User\AdminController::class)->group(function () {
	Route::get('admin/start', 'adminStart');
	Route::post('admin/login', 'login');
	Route::post('admin/logout', 'logout');
});

Route::middleware('auth:admin')->group(function () {
	Route::resource('admin', AdminController::class);
});

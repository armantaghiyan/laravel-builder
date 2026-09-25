<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['rateLimit:15'])->prefix('admin')->group(function () {
	Route::controller(\App\Http\Controllers\Admin\AdminController::class)->group(function () {
		Route::get('admin/start', 'adminStart');
		Route::post('admin/login', 'login');
		Route::post('admin/logout', 'logout');
	});

	Route::middleware('auth:admin')->group(function () {
		Route::controller(\App\Http\Controllers\Admin\AdminController::class)->group(function () {
			Route::get('admin/profile', 'profile');
			Route::patch('admin/profile/password', 'changePassword');
		});

		Route::resource('admin', \App\Http\Controllers\Admin\AdminController::class);

		Route::controller(\App\Http\Controllers\Admin\AccessController::class)->group(function () {
			Route::get('access/role/{id}', 'showRole');
			Route::post('access/role-toggle', 'roleToggle');
			Route::post('access/permission-toggle', 'permissionToggle');
			Route::resource('access', \App\Http\Controllers\Admin\AccessController::class);
		});

		Route::controller(\App\Http\Controllers\Admin\LogController::class)->group(function () {
			Route::get('log/report', 'report');
			Route::resource('log', \App\Http\Controllers\Admin\LogController::class);
		});
	});
});

Route::view('/{any?}', 'admin.main')->where('any', '.*');

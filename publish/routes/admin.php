<?php

use Illuminate\Support\Facades\Route;

Route::controller(App\Services\Domain\User\Admin\Controllers\AdminController::class)->group(function () {
	Route::get('admin/start', 'adminStart');
	Route::post('admin/login', 'login');
	Route::post('admin/logout', 'logout');
});

Route::middleware('auth:admin')->group(function () {
	Route::resource('admin', App\Services\Domain\User\Admin\Controllers\AdminController::class);

	Route::controller(App\Services\Domain\User\Access\Controllers\AccessController::class)->group(function () {
		Route::get('access/role/{id}', 'showRole');
		Route::post('access/role-toggle', 'roleToggle');
		Route::post('access/permission-toggle', 'permissionToggle');
		Route::resource('access', App\Services\Domain\User\Access\Controllers\AccessController::class);
	});
});

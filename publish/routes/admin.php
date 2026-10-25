<?php

use Illuminate\Support\Facades\Route;


Route::prefix('admin/admin')->group(function () {
	Route::get('login/captcha', \App\Http\Controllers\Admin\AdminLoginCaptchaController::class)->middleware('rateLimit:15');
	Route::post('login', [\App\Http\Controllers\Admin\AdminController::class, 'login'])->middleware(['rateLimit:3']);
});

Route::middleware(['rateLimit:15'])->prefix('admin')->group(function () {
	Route::controller(\App\Http\Controllers\Admin\AdminController::class)->group(function () {
		Route::get('admin/start', 'adminStart');
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

		Route::controller(\App\Http\Controllers\Notification\NotificationInboxController::class)->group(function () {
			Route::get('notification/inbox', 'index');
			Route::patch('notification/inbox/{id}/read', 'read')->whereNumber('id');
		});

		Route::resource('notification', \App\Http\Controllers\Admin\NotificationController::class)
			->only(['index', 'store', 'show', 'update', 'destroy'])->whereNumber('notification');

		Route::controller(\App\Http\Controllers\Admin\LogFileController::class)->group(function () {
			Route::get('log-files', 'index');
			Route::get('log-files/show', 'show');
			Route::get('log-files/download', 'download');
		});

		Route::controller(\App\Http\Controllers\Admin\AdminSessionController::class)->group(function () {
			Route::get('admin/profile/sessions', 'profileIndex');
			Route::delete('admin/profile/sessions/{id}', 'profileDestroy')->whereNumber('id');
			Route::get('admin/{adminId}/sessions', 'index')->whereNumber('adminId');
			Route::delete('admin/{adminId}/sessions/{id}', 'destroy')->whereNumber(['adminId', 'id']);
		});
	});
});

Route::view('/{any?}', 'admin.main')->where('any', '.*');

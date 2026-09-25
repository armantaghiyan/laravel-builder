<?php

namespace App\Providers;

use App\Core\Domain\Admin\Models\Admin;
use App\Http\Constants\Permissions;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Register any application services.
	 */
	public function register(): void {
		//
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void {

		Gate::before(function ($user, $ability) {
			return $user->hasPermissionTo(Permissions::ADMIN_SUPER_ADMIN) ? true : null;
		});

		Relation::enforceMorphMap([
			Admin::MORPH_NAME => Admin::class,
			'role' => Role::class,
		]);
	}
}

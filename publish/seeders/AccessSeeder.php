<?php

namespace Database\Seeders;

use App\Helpers\Permissions;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AccessSeeder extends Seeder {

	/**
	 * Create the initial roles and permissions.
	 */
	public function run(): void {
		$role = Role::create([
			'name' => 'admin',
			'guard_name' => 'admin',
		]);

		$permissions = [
			Permissions::ADMIN_INDEX,
			Permissions::ADMIN_UPDATE,
			Permissions::ADMIN_STORE,
		];

		foreach ($permissions as $permission) {
			$perm = Permission::create([
				'name' => $permission,
				'guard_name' => 'admin',
			]);
			$role->givePermissionTo($perm);
		}
	}
}

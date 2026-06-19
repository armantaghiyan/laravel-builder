<?php

namespace Database\Seeders;

use App\Http\Constants\Permissions;
use Illuminate\Database\Seeder;
use ReflectionClass;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AccessSeeder extends Seeder {

	/**
	 * Create the initial roles and permissions.
	 */
	public function run(): void {
		Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'admin']);

		$reflection = new ReflectionClass(Permissions::class);
		$permissions = $reflection->getConstants();

		foreach ($permissions as $permission) {
			Permission::firstOrCreate(
				['name' => $permission, 'guard_name' => 'admin']
			);
		}
	}
}

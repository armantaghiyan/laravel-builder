<?php

namespace Database\Seeders;

use App\Core\Domain\Access\Repositories\AccessRepository;
use App\Http\Constants\Permissions;
use Illuminate\Database\Seeder;
use ReflectionClass;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AccessSeeder extends Seeder {

	public function __construct(
		private AccessRepository $accessRepository,
	) {

	}

	/**
	 * Create the initial roles and permissions.
	 */
	public function run(): void {
		$role = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'admin']);

		$reflection = new ReflectionClass(Permissions::class);
		$permissions = $reflection->getConstants();

		foreach ($permissions as $permission) {
			Permission::firstOrCreate(
				['name' => $permission, 'guard_name' => 'admin']
			);
		}

		$permission = Permission::where('name', Permissions::ADMIN_SUPER_ADMIN)->first();

		if (!$this->accessRepository->roleHasPermission($role, $permission)) {
			$this->accessRepository->givePermission($role, $permission);
		}
	}
}

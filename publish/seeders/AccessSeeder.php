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
        Role::create(['name' => 'Super Admin', 'guard_name' => 'admin']);

        $permissions = [
            Permissions::ADMIN_INDEX,
            Permissions::ADMIN_UPDATE,
            Permissions::ADMIN_STORE,
            Permissions::ADMIN_ADD_ROLE,

            Permissions::ROLE_INDEX,
            Permissions::ROLE_UPDATE,
            Permissions::ROLE_STORE,
            Permissions::ROLE_DESTROY,
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'admin']);
        }
    }
}

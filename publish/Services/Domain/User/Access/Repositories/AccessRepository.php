<?php

namespace App\Services\Domain\User\Access\Repositories;

use App\Services\Domain\User\Admin\Models\Admin;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AccessRepository {

    public function getAllRoles(string $guard) {
        return Role::where('guard_name', $guard)->get(['id', 'name', 'created_at', 'updated_at']);
    }

    public function findRoleById(int $id): ?Role {
        return Role::find($id);
    }

    public function getRolePermissions(Role $role) {
        return $role->permissions()->get(['id', 'name'])->makeHidden(['pivot', 'updated_at', 'created_at', 'guard_name']);
    }

    public function getAdminById(int $id): Admin {
        return Admin::where(Admin::ID, $id)->firstOrError();
    }

    public function getAdminRoles(Admin $admin) {
        return $admin->roles()->get(['id', 'name'])->makeHidden(['pivot']);
    }

    public function getPermissions(string $guard) {
        return Permission::where('guard_name', $guard)->get(['id', 'name'])->makeHidden(['pivot']);
    }

    public function createRole(string $name, string $guard): Role {
        return Role::create([
            'name' => $name,
            'guard_name' => $guard,
        ]);
    }

    public function updateRole(Role $role, string $name): Role {
        $role->name = $name;
        $role->save();
        return $role;
    }

    public function deleteRole(int $id): void {
        Role::where('id', $id)->delete();
    }

    public function findPermissionById(int $id): ?Permission {
        return Permission::find($id);
    }

    public function roleHasPermission(Role $role, Permission $permission): bool {
        return $role->hasPermissionTo($permission);
    }

    public function revokePermission(Role $role, Permission $permission): void {
        $role->revokePermissionTo($permission);
    }

    public function givePermission(Role $role, Permission $permission): void {
        $role->givePermissionTo($permission);
    }

    public function adminHasRole(Admin $admin, string $roleName): bool {
        return $admin->hasRole($roleName);
    }

    public function assignRole(Admin $admin, string $roleName): void {
        $admin->assignRole($roleName);
    }

    public function removeRole(Admin $admin, string $roleName): void {
        $admin->removeRole($roleName);
    }
}

<?php

namespace App\Services\Domain\Access;

use App\Dto\App\Access\AccessStoreData;
use App\Dto\App\Access\AccessUpdateData;
use App\Exceptions\ErrorMessageException;
use App\Helpers\StatusCodes;
use App\Models\User\Admin;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AccessService {


    public function getAllRolls($guard) {
        return Role::where('guard_name', $guard)->get(['id', 'name', 'created_at', 'updated_at']);
    }

    public function getAdminRoles($adminId) {
        $admin = Admin::where(Admin::ID, $adminId)->firstOrError();

        if ($admin) {
            return $admin->roles()->get(['id', 'name'])->makeHidden(['pivot']);
        }

        return null;
    }

    public function getPermissions($guard) {
        $admin = Auth::guard($guard)->user();

        if ($admin) {
            return Permission::where('guard_name', $guard)->get(['id', 'name'])->makeHidden(['pivot']);
        }

        return [];
    }

    /**
     * @throws ErrorMessageException
     */
    public function getPermissionsRole($roleId) {
        $role = Role::find($roleId);
        if (!$role){
            throw new ErrorMessageException(__('error.unexpected_error'), StatusCodes::Conflict);
        }

        return $role->permissions()->get(['id', 'name'])->makeHidden(['pivot', 'updated_at', 'created_at', 'guard_name']);
    }

    /**
     * @throws ErrorMessageException
     */
    public function toggleAdminRole(int $adminId, int $roleId): void {
        $admin = Admin::where(Admin::ID, $adminId)->firstOrError();
        $role = Role::find($roleId);
        if (!$role){
            throw new ErrorMessageException(__('error.unexpected_error'), StatusCodes::Conflict);
        }

        if ($admin->hasRole($role->name)) {
            $admin->removeRole($role->name);
        } else {
            $admin->assignRole($role->name);
        }
    }

    /**
     * @throws ErrorMessageException
     */
    public function togglePermission(int $permissionId, int $roleId): void {
        $role = Role::find($roleId);
        $permission = Permission::find($permissionId);

        if (!$role || !$permission) {
            throw new ErrorMessageException(__('error.unexpected_error'), StatusCodes::Conflict);
        }

        if ($role->hasPermissionTo($permission)) {
            $role->revokePermissionTo($permission);
        } else {
            $role->givePermissionTo($permission);
        }
    }

    /**
     * @throws ErrorMessageException
     */
    public function showRole(int $roleId) {
        $role = Role::find($roleId);
        if (!$role){
            throw new ErrorMessageException(__('error.unexpected_error'), StatusCodes::Conflict);
        }

        return $role;
    }

    public function store(AccessStoreData $data) {
        $role = Role::create(['name' => $data->name, 'guard_name' => 'admin']);

        return $role;
    }

    public function update(int $id, AccessUpdateData $data) {
        $role = Role::find($id);
        if (!$role){
            throw new ErrorMessageException(__('error.unexpected_error'), StatusCodes::Conflict);
        }

        $role['name'] = $data->name;
        $role->save();

        return $role;
    }

    public function destroy($id) {
        Role::where('id', $id)->delete();
    }
}

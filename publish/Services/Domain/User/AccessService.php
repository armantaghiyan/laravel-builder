<?php

namespace App\Services\Domain\User;

use App\Dto\App\Access\AccessStoreData;
use App\Dto\App\Access\AccessUpdateData;
use App\Exceptions\ErrorMessageException;
use App\Helpers\StatusCodes;
use Illuminate\Support\Facades\Auth;
use User\AccessRepository;

class AccessService {

    public function __construct(
        private readonly AccessRepository $repository
    ) {
    }

    public function getAllRolls($guard) {
        return $this->repository->getAllRoles($guard);
    }

    public function getAdminRoles($adminId) {
        $admin = $this->repository->getAdminById($adminId);
        return $this->repository->getAdminRoles($admin);
    }

    public function getPermissions($guard) {
        $admin = Auth::guard($guard)->user();
        return $admin ? $this->repository->getPermissions($guard) : [];
    }

    public function getUserPermissions($guard) {
        $admin = Auth::guard($guard)->user();
        if (!$admin) return [];

        if ($admin->hasRole('Super Admin')) {
            return $this->repository->getPermissions($guard);
        }

        return $admin->getAllPermissions();
    }

    /**
     * @throws ErrorMessageException
     */
    public function getPermissionsRole($roleId) {
        $role = $this->repository->findRoleById($roleId);
        if (!$role) {
            throw new ErrorMessageException(__('error.unexpected_error'), StatusCodes::Conflict);
        }

        return $this->repository->getRolePermissions($role);
    }

    /**
     * @throws ErrorMessageException
     */
    public function toggleAdminRole(int $adminId, int $roleId): void {
        $admin = $this->repository->getAdminById($adminId);
        $role = $this->repository->findRoleById($roleId);

        if (!$role) {
            throw new ErrorMessageException(__('error.unexpected_error'), StatusCodes::Conflict);
        }

        if ($this->repository->adminHasRole($admin, $role->name)) {
            $this->repository->removeRole($admin, $role->name);
        } else {
            $this->repository->assignRole($admin, $role->name);
        }
    }

    /**
     * @throws ErrorMessageException
     */
    public function togglePermission(int $permissionId, int $roleId): void {
        $role = $this->repository->findRoleById($roleId);
        $permission = $this->repository->findPermissionById($permissionId);

        if (!$role || !$permission) {
            throw new ErrorMessageException(__('error.unexpected_error'), StatusCodes::Conflict);
        }

        if ($this->repository->roleHasPermission($role, $permission)) {
            $this->repository->revokePermission($role, $permission);
        } else {
            $this->repository->givePermission($role, $permission);
        }
    }

    /**
     * @throws ErrorMessageException
     */
    public function showRole(int $roleId) {
        $role = $this->repository->findRoleById($roleId);
        if (!$role) {
            throw new ErrorMessageException(__('error.unexpected_error'), StatusCodes::Conflict);
        }

        return $role;
    }

    public function store(AccessStoreData $data) {
        return $this->repository->createRole($data->name, 'admin');
    }

    public function update(int $id, AccessUpdateData $data) {
        $role = $this->repository->findRoleById($id);
        if (!$role) {
            throw new ErrorMessageException(__('error.unexpected_error'), StatusCodes::Conflict);
        }

        return $this->repository->updateRole($role, $data->name);
    }

    public function destroy($id) {
        $this->repository->deleteRole($id);
    }
}

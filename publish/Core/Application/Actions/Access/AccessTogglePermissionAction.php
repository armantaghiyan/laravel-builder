<?php

namespace App\Core\Application\Actions\Access;

use App\Core\Domain\Access\Repositories\AccessRepository;
use App\Core\Domain\Common\Constants\StatusCodes;
use App\Core\Infrastructure\Exceptions\ErrorMessageException;

readonly class AccessTogglePermissionAction {

    public function __construct(
        private AccessRepository $repository,
    ) {
    }

    /**
     * @throws ErrorMessageException
     */
    public function execute(int $permissionId, int $roleId): void {
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
}

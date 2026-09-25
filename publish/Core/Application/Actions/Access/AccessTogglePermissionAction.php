<?php

namespace App\Core\Application\Actions\Access;

use App\Core\Domain\Access\Repositories\AccessRepository;
use App\Core\Domain\Common\Constants\StatusCodes;
use App\Core\Domain\Logger\Constants\LogEvent;
use App\Core\Infrastructure\Exceptions\ErrorMessageException;
use App\Core\Infrastructure\Services\Logger;

readonly class AccessTogglePermissionAction {

    public function __construct(
        private AccessRepository $repository,
        private Logger           $logger,
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
            $status = 'غیرفعال';
        } else {
            $this->repository->givePermission($role, $permission);
            $status = 'فعال';
        }

        $this->logger->log(
            LogEvent::PermissionStatusChanged,
            "دسترسی «{$permission->name}» (شناسه: {$permission->getKey()}) برای نقش «{$role->name}» (شناسه: {$role->getKey()}) {$status} شد.",
        );
    }
}

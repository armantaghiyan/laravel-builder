<?php

namespace App\Core\Application\Actions\Access;

use App\Core\Domain\Access\Repositories\AccessRepository;
use App\Core\Domain\Admin\Models\Admin;
use App\Core\Domain\Common\Constants\StatusCodes;
use App\Core\Domain\Logger\Constants\LogEvent;
use App\Core\Infrastructure\Exceptions\ErrorMessageException;
use App\Core\Infrastructure\Services\Logger;

readonly class AccessToggleAdminRoleAction {

    public function __construct(
        private AccessRepository $repository,
        private Logger           $logger,
    ) {
    }

    /**
     * @throws ErrorMessageException
     */
    public function execute(int $adminId, int $roleId): void {
        $admin = $this->repository->getAdminById($adminId);
        $role = $this->repository->findRoleById($roleId);

        if (!$role) {
            throw new ErrorMessageException(__('error.unexpected_error'), StatusCodes::Conflict);
        }

        if ($this->repository->adminHasRole($admin, $role->name)) {
            $this->repository->removeRole($admin, $role->name);
            $status = 'غیرفعال';
        } else {
            $this->repository->assignRole($admin, $role->name);
            $status = 'فعال';
        }

        $this->logger->log(
            LogEvent::AdminRoleStatusChanged,
            "نقش «{$role->name}» (شناسه: {$role->getKey()}) برای مدیر «{$admin[Admin::NAME]}» (شناسه: {$admin[Admin::ID]}) {$status} شد.",
            $admin,
        );
    }
}

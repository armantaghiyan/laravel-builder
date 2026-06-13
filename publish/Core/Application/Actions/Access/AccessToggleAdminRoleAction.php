<?php

namespace App\Core\Application\Actions\Access;

use App\Core\Domain\Access\Repositories\AccessRepository;
use App\Core\Domain\Common\Constants\StatusCodes;
use App\Core\Infrastructure\Exceptions\ErrorMessageException;

readonly class AccessToggleAdminRoleAction {

    public function __construct(
        private AccessRepository $repository,
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
        } else {
            $this->repository->assignRole($admin, $role->name);
        }
    }
}

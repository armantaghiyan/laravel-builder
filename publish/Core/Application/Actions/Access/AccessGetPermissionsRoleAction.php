<?php

namespace App\Core\Application\Actions\Access;

use App\Core\Domain\Access\Repositories\AccessRepository;
use App\Core\Domain\Common\Constants\StatusCodes;
use App\Core\Infrastructure\Exceptions\ErrorMessageException;

readonly class AccessGetPermissionsRoleAction {

    public function __construct(
        private AccessRepository $repository,
    ) {
    }

    /**
     * @throws ErrorMessageException
     */
    public function execute(int $roleId): mixed {
        $role = $this->repository->findRoleById($roleId);
        if (!$role) {
            throw new ErrorMessageException(__('error.unexpected_error'), StatusCodes::Conflict);
        }

        return $this->repository->getRolePermissions($role);
    }
}

<?php

namespace App\Core\Application\Actions\Access;

use App\Core\Domain\Access\Repositories\AccessRepository;

readonly class AccessGetAdminRolesAction {

    public function __construct(
        private AccessRepository $repository
    ) {
    }

    public function execute($adminId): mixed {
        $admin = $this->repository->getAdminById($adminId);
        return $this->repository->getAdminRoles($admin);
    }
}

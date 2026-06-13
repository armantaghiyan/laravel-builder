<?php

namespace App\Core\Application\Actions\Access;

use App\Core\Domain\Access\Repositories\AccessRepository;

readonly class AccessGetAllRolesAction {

    public function __construct(
        private AccessRepository $repository,
    ) {
    }

    public function execute(string $guard): mixed {
        return $this->repository->getAllRoles($guard);
    }
}

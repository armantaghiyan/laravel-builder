<?php

namespace App\Core\Application\Actions\Access;

use App\Core\Domain\Access\Repositories\AccessRepository;

readonly class AccessDestroyAction {

    public function __construct(
        private AccessRepository $repository,
    ) {
    }

    public function execute(int $id): void {
        $this->repository->deleteRole($id);
    }
}

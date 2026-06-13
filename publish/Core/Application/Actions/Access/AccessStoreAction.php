<?php

namespace App\Core\Application\Actions\Access;

use App\Core\Domain\Access\Repositories\AccessRepository;
use App\Http\Data\Admin\Access\AccessStoreData;

readonly class AccessStoreAction {

    public function __construct(
        private AccessRepository $repository,
    ) {
    }

    public function execute(AccessStoreData $data): mixed {
        return $this->repository->createRole($data->name, 'admin');
    }
}

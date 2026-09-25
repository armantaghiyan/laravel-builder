<?php

namespace App\Core\Application\Actions\Access;

use App\Core\Domain\Access\Repositories\AccessRepository;
use App\Core\Domain\Logger\Constants\LogEvent;
use App\Core\Infrastructure\Services\Logger;
use App\Http\Data\Admin\Access\AccessStoreData;

readonly class AccessStoreAction {

    public function __construct(
        private AccessRepository $repository,
        private Logger           $logger,
    ) {
    }

    public function execute(AccessStoreData $data): mixed {
        $role = $this->repository->createRole($data->name, 'admin');

        $this->logger->log(
            LogEvent::AccessCreated,
            "نقش دسترسی «{$role->name}» (شناسه: {$role->getKey()}) ایجاد شد.",
        );

        return $role;
    }
}

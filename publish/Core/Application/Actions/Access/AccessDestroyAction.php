<?php

namespace App\Core\Application\Actions\Access;

use App\Core\Domain\Access\Repositories\AccessRepository;
use App\Core\Domain\Logger\Constants\LogEvent;
use App\Core\Infrastructure\Services\Logger;

readonly class AccessDestroyAction {

    public function __construct(
        private AccessRepository $repository,
        private Logger           $logger,
    ) {
    }

    public function execute(int $id): void {
        $role = $this->repository->findRoleById($id);
        $this->repository->deleteRole($id);

        $message = $role
            ? "نقش دسترسی «{$role->name}» (شناسه: {$role->getKey()}) حذف شد."
            : "نقش دسترسی با شناسه {$id} حذف شد.";

        $this->logger->log(
            LogEvent::AccessDeleted,
            $message,
        );
    }
}

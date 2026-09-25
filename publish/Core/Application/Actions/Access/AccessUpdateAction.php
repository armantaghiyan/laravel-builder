<?php

namespace App\Core\Application\Actions\Access;

use App\Core\Domain\Access\Repositories\AccessRepository;
use App\Core\Domain\Common\Constants\StatusCodes;
use App\Core\Domain\Logger\Constants\LogEvent;
use App\Core\Infrastructure\Exceptions\ErrorMessageException;
use App\Core\Infrastructure\Services\Logger;
use App\Http\Data\Admin\Access\AccessUpdateData;

readonly class AccessUpdateAction {

    public function __construct(
        private AccessRepository $repository,
        private Logger           $logger,
    ) {
    }

    /**
     * @throws ErrorMessageException
     */
    public function execute(int $id, AccessUpdateData $data): mixed {
        $role = $this->repository->findRoleById($id);
        if (!$role) {
            throw new ErrorMessageException(__('error.unexpected_error'), StatusCodes::Conflict);
        }

        $role = $this->repository->updateRole($role, $data->name);

        $this->logger->log(
            LogEvent::AccessUpdated,
            "اطلاعات نقش دسترسی «{$role->name}» (شناسه: {$role->getKey()}) به‌روزرسانی شد.",
        );

        return $role;
    }
}

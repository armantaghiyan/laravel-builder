<?php

namespace App\Core\Application\Actions\Access;

use App\Core\Domain\Access\Repositories\AccessRepository;
use App\Core\Domain\Common\Constants\StatusCodes;
use App\Core\Infrastructure\Exceptions\ErrorMessageException;
use App\Http\Data\Admin\Access\AccessUpdateData;

readonly class AccessUpdateAction {

    public function __construct(
        private AccessRepository $repository,
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

        return $this->repository->updateRole($role, $data->name);
    }
}

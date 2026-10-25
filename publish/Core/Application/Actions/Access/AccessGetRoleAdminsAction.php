<?php

namespace App\Core\Application\Actions\Access;

use App\Core\Domain\Access\Repositories\AccessRepository;
use App\Core\Domain\Admin\Repositories\AdminRepository;
use App\Core\Domain\Common\Constants\StatusCodes;
use App\Core\Infrastructure\Exceptions\ErrorMessageException;
use App\Http\Data\Admin\Access\AccessAdminIndexData;

readonly class AccessGetRoleAdminsAction
{
    public function __construct(
        private AccessRepository $accessRepository,
        private AdminRepository $adminRepository,
    ) {}

    public function execute(int $roleId, AccessAdminIndexData $data): array
    {
        $role = $this->accessRepository->findRoleById($roleId);
        if (! $role || $role->guard_name !== 'admin') {
            throw new ErrorMessageException(__('error.unexpected_error'), StatusCodes::Not_found);
        }

        return $this->adminRepository->getRoleAdmins($role, $data);
    }
}

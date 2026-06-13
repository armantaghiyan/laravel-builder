<?php

namespace App\Core\Application\Actions\Admin;

use App\Core\Application\Actions\Access\AccessGetAdminRolesAction;
use App\Core\Application\Actions\Access\AccessGetAllRolesAction;
use App\Core\Domain\Admin\Repositories\AdminRepository;

readonly class AdminShowAction {

    public function __construct(
        private AdminRepository           $adminRepository,
        private AccessGetAllRolesAction   $accessGetAllRolesAction,
        private AccessGetAdminRolesAction $accessGetAdminRolesAction,
    ) {
    }

    public function execute(int $id): array {
        $admin = $this->adminRepository->findById($id);
        $roles = $this->accessGetAllRolesAction->execute('admin');
        $adminRoles = $this->accessGetAdminRolesAction->execute($id);

        return [$admin, $roles, $adminRoles];
    }
}

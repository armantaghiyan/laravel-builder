<?php

namespace App\Core\Application\Actions\Admin;


use App\Core\Application\Actions\Access\AccessGetPermissionAction;
use App\Core\Application\Actions\Access\AccessGetUserPermissionAction;
use App\Core\Infrastructure\Exceptions\ErrorMessageException;

readonly class AdminStartAction {

    public function __construct(
        private AdminProfileAction            $adminProfileAction,
        private AccessGetPermissionAction     $accessGetPermissionAction,
        private AccessGetUserPermissionAction $accessGetUserPermissionAction,
    ) {
    }

    /**
     * @throws ErrorMessageException
     */
    public function execute(): array {
        $admin = $this->adminProfileAction->execute();
        $permissions = $this->accessGetPermissionAction->execute('admin');
        $adminPermissions = $this->accessGetUserPermissionAction->execute('admin');

        return [$admin, $permissions, $adminPermissions];
    }
}

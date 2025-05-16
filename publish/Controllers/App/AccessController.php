<?php

namespace App\Http\Controllers\App;

use App\Dto\App\Access\AccessPermissionToggleData;
use App\Dto\App\Access\AccessRoleToggleData;
use App\Http\Controllers\Controller;
use App\Http\Resources\App\Access\AccessIndexResource;
use App\Http\Resources\App\Access\AccessShowResource;
use App\Http\Resources\GlobalResources\SuccessResource;
use App\Services\Domain\AccessService;

class AccessController extends Controller {

    public function __construct(
        private AccessService $accessService
    ) {

    }

    public function index() {
        $roles = $this->accessService->getAllRolls('admin');

        return new AccessIndexResource([RK_ITEMS => $roles]);
    }

    public function roleToggle(AccessRoleToggleData $data) {
        $this->accessService->toggleAdminRole($data->admin_id, $data->role_id);

        return new SuccessResource([]);
    }

    public function permissionToggle(AccessPermissionToggleData $data) {
        $this->accessService->togglePermission($data->permission_id, $data->role_id);

        return new SuccessResource([]);
    }

    public function show($id) {
        $permissions = $this->accessService->getPermissionsRole($id);

        return new AccessShowResource([RK_PERMISSIONS => $permissions]);
    }
}

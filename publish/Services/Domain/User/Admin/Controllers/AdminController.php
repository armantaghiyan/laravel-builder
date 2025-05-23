<?php

namespace App\Services\Domain\User\Admin\Controllers;

use App\Exceptions\ErrorMessageException;
use App\Helpers\Permissions;
use App\Services\Domain\User\Access\Services\AccessService;
use App\Services\Domain\User\Admin\Dto\AdminIndexData;
use App\Services\Domain\User\Admin\Dto\AdminLoginData;
use App\Services\Domain\User\Admin\Dto\AdminStoreData;
use App\Services\Domain\User\Admin\Dto\AdminUpdateData;
use App\Services\Domain\User\Admin\Resources\AdminIndexResource;
use App\Services\Domain\User\Admin\Resources\AdminLoginResource;
use App\Services\Domain\User\Admin\Resources\AdminShowResource;
use App\Services\Domain\User\Admin\Resources\AdminStartResource;
use App\Services\Domain\User\Admin\Resources\AdminStoreResource;
use App\Services\Domain\User\Admin\Resources\AdminUpdateResource;
use App\Services\Domain\User\Admin\Services\AdminService;
use App\Services\Infrastructure\Resources\SuccessResource;
use Illuminate\Routing\Controller;

class AdminController extends Controller {


    public function __construct(
        private readonly AdminService  $adminService,
        private readonly AccessService $accessService,
    ) {

        $this->middleware('permission:' . Permissions::ADMIN_INDEX)->only(['index', 'show']);
        $this->middleware('permission:' . Permissions::ADMIN_STORE)->only(['store']);
        $this->middleware('permission:' . Permissions::ADMIN_UPDATE)->only(['update']);
    }

    public function index(AdminIndexData $data): AdminIndexResource {
        [$items, $count] = $this->adminService->index($data);

        return new AdminIndexResource([RK_ITEMS => $items, RK_COUNT => $count]);
    }

    public function store(AdminStoreData $data): AdminStoreResource {
        $item = $this->adminService->store($data);

        return new AdminStoreResource([RK_ITEM => $item]);
    }

    public function show($id): AdminShowResource {
        $item = $this->adminService->show($id);
        $adminRoles = $this->accessService->getAdminRoles($id);
        $roles = $this->accessService->getAllRoles('admin');

        return new AdminShowResource([RK_ITEM => $item, RK_ADMIN_ROLES => $adminRoles, RK_ROLES => $roles]);
    }

    public function update(AdminUpdateData $data, $id): AdminUpdateResource {
        $item = $this->adminService->update($data, $id);

        return new AdminUpdateResource([RK_ITEM => $item]);
    }

    public function destroy($id): SuccessResource {
        $this->adminService->destroy($id);

        return new SuccessResource([]);
    }

    /**
     * @throws ErrorMessageException
     */
    public function login(AdminLoginData $data) {
        [$admin, $apiToken] = $this->adminService->login($data);


        return new AdminLoginResource([
            RK_API_TOKEN => $apiToken,
            RK_ADMIN => $admin,
        ]);
    }

    public function adminStart() {
        $admin = $this->adminService->profile();

        $permissions = $this->accessService->getPermissions('admin');
        $adminPermissions = $this->accessService->getUserPermissions('admin');

        return new AdminStartResource([RK_ADMIN => $admin, RK_PERMISSIONS => $permissions, RK_ADMIN_PERMISSIONS => $adminPermissions]);
    }

    public function logout() {
        $this->adminService->logout();

        return new SuccessResource([]);
    }
}

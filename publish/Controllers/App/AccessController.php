<?php

namespace App\Http\Controllers\App;

use App\Dto\App\Access\AccessPermissionToggleData;
use App\Dto\App\Access\AccessRoleToggleData;
use App\Dto\App\Access\AccessStoreData;
use App\Dto\App\Access\AccessUpdateData;
use App\Exceptions\ErrorMessageException;
use App\Helpers\Permissions;
use App\Http\Resources\App\Access\AccessIndexResource;
use App\Http\Resources\App\Access\AccessShowResource;
use App\Http\Resources\App\Access\AccessShowRoleResource;
use App\Http\Resources\App\Access\AccessStoreResource;
use App\Http\Resources\App\Access\AccessUpdateResource;
use App\Http\Resources\GlobalResources\SuccessResource;
use App\Services\Domain\Access\AccessService;
use Illuminate\Routing\Controller;

class AccessController extends Controller {

	public function __construct(
		private AccessService $accessService
	) {
        $this->middleware('permission:' . Permissions::ROLE_INDEX)->only(['index', 'show']);
        $this->middleware('permission:' . Permissions::ROLE_UPDATE)->only(['update', 'roleToggle', 'permissionToggle']);
        $this->middleware('permission:' . Permissions::ROLE_STORE)->only(['store']);
        $this->middleware('permission:' . Permissions::ROLE_DESTROY)->only(['destroy']);
	}

	public function index() {
		$roles = $this->accessService->getAllRolls('admin');

		return new AccessIndexResource([RK_ITEMS => $roles]);
	}

    /**
     * @throws ErrorMessageException
     */
    public function roleToggle(AccessRoleToggleData $data) {
		$this->accessService->toggleAdminRole($data->admin_id, $data->role_id);

		return new SuccessResource([]);
	}

    /**
     * @throws ErrorMessageException
     */
    public function permissionToggle(AccessPermissionToggleData $data) {
		$this->accessService->togglePermission($data->permission_id, $data->role_id);

		return new SuccessResource([]);
	}

	public function show($id) {
		$permissions = $this->accessService->getPermissionsRole($id);

		return new AccessShowResource([RK_PERMISSIONS => $permissions]);
	}

    /**
     * @throws ErrorMessageException
     */
    public function showRole($id) {
		$role = $this->accessService->showRole($id);

		return new AccessShowRoleResource([RK_ITEM => $role]);
	}

    public function store(AccessStoreData $data) {
        $role = $this->accessService->store($data);

        return new AccessStoreResource([RK_ITEM => $role]);
    }

    /**
     * @throws ErrorMessageException
     */
    public function update(AccessUpdateData $data, $id) {
        $role = $this->accessService->update($id, $data);

        return new AccessUpdateResource([RK_ITEM => $role]);
    }

    public function destroy($id) {
        $this->accessService->destroy($id);

        return new SuccessResource([]);
    }
}

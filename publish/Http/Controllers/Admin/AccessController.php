<?php

namespace App\Http\Controllers\Admin;

use App\Core\Application\Actions\Access\AccessDestroyAction;
use App\Core\Application\Actions\Access\AccessGetAllRolesAction;
use App\Core\Application\Actions\Access\AccessGetPermissionsRoleAction;
use App\Core\Application\Actions\Access\AccessShowRoleAction;
use App\Core\Application\Actions\Access\AccessStoreAction;
use App\Core\Application\Actions\Access\AccessToggleAdminRoleAction;
use App\Core\Application\Actions\Access\AccessTogglePermissionAction;
use App\Core\Application\Actions\Access\AccessUpdateAction;
use App\Core\Domain\Access\Constants\Permissions;
use App\Core\Infrastructure\Exceptions\ErrorMessageException;
use App\Http\Data\Admin\Access\AccessPermissionToggleData;
use App\Http\Data\Admin\Access\AccessRoleToggleData;
use App\Http\Data\Admin\Access\AccessStoreData;
use App\Http\Data\Admin\Access\AccessUpdateData;
use App\Http\Resources\Admin\Access\AccessIndexResource;
use App\Http\Resources\Admin\Access\AccessShowResource;
use App\Http\Resources\Admin\Access\AccessShowRoleResource;
use App\Http\Resources\Admin\Access\AccessStoreResource;
use App\Http\Resources\Admin\Access\AccessUpdateResource;
use App\Http\Resources\SuccessResource;
use Illuminate\Routing\Controller;

class AccessController extends Controller {

	public function __construct(
		private readonly AccessGetAllRolesAction        $getAllRolesAction,
		private readonly AccessGetPermissionsRoleAction $getPermissionsRoleAction,
		private readonly AccessShowRoleAction           $showRoleAction,
		private readonly AccessToggleAdminRoleAction    $toggleAdminRoleAction,
		private readonly AccessTogglePermissionAction   $togglePermissionAction,
		private readonly AccessStoreAction              $storeAction,
		private readonly AccessUpdateAction             $updateAction,
		private readonly AccessDestroyAction            $destroyAction,
	) {
		$this->middleware('permission:' . Permissions::ROLE_INDEX)->only(['index', 'show']);
		$this->middleware('permission:' . Permissions::ROLE_UPDATE)->only(['update', 'roleToggle', 'permissionToggle']);
		$this->middleware('permission:' . Permissions::ROLE_STORE)->only(['store']);
		$this->middleware('permission:' . Permissions::ROLE_DESTROY)->only(['destroy']);
	}

	public function index(): AccessIndexResource {
		$roles = $this->getAllRolesAction->execute('admin');

		return new AccessIndexResource($roles);
	}

	/**
	 * @throws ErrorMessageException
	 */
	public function roleToggle(AccessRoleToggleData $data): SuccessResource {
		$this->toggleAdminRoleAction->execute($data->admin_id, $data->role_id);

		return new SuccessResource([]);
	}

	/**
	 * @throws ErrorMessageException
	 */
	public function permissionToggle(AccessPermissionToggleData $data): SuccessResource {
		$this->togglePermissionAction->execute($data->permission_id, $data->role_id);

		return new SuccessResource([]);
	}

	/**
	 * @throws ErrorMessageException
	 */
	public function show(int $id): AccessShowResource {
		$permissions = $this->getPermissionsRoleAction->execute($id);

		return new AccessShowResource($permissions);
	}

	/**
	 * @throws ErrorMessageException
	 */
	public function showRole(int $id): AccessShowRoleResource {
		$role = $this->showRoleAction->execute($id);

		return new AccessShowRoleResource($role);
	}

	public function store(AccessStoreData $data): AccessStoreResource {
		$role = $this->storeAction->execute($data);

		return new AccessStoreResource($role);
	}

	/**
	 * @throws ErrorMessageException
	 */
	public function update(AccessUpdateData $data, int $id): AccessUpdateResource {
		$role = $this->updateAction->execute($id, $data);

		return new AccessUpdateResource($role);
	}

	public function destroy(int $id): SuccessResource {
		$this->destroyAction->execute($id);

		return new SuccessResource([]);
	}
}

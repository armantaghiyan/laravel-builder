<?php

namespace App\Services\Domain\User\Access\Controllers;

use App\Services\Domain\User\Access\Constants\Permissions;
use App\Services\Domain\User\Access\Dto\AccessPermissionToggleData;
use App\Services\Domain\User\Access\Dto\AccessRoleToggleData;
use App\Services\Domain\User\Access\Dto\AccessStoreData;
use App\Services\Domain\User\Access\Dto\AccessUpdateData;
use App\Services\Domain\User\Access\Resources\AccessIndexResource;
use App\Services\Domain\User\Access\Resources\AccessShowResource;
use App\Services\Domain\User\Access\Resources\AccessShowRoleResource;
use App\Services\Domain\User\Access\Resources\AccessStoreResource;
use App\Services\Domain\User\Access\Resources\AccessUpdateResource;
use App\Services\Domain\User\Access\Services\AccessService;
use App\Services\Infrastructure\Exceptions\ErrorMessageException;
use App\Services\Infrastructure\Resources\SuccessResource;
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
		$roles = $this->accessService->getAllRoles('admin');

		return new AccessIndexResource($roles);
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

		return new AccessShowResource($permissions);
	}

	/**
	 * @throws ErrorMessageException
	 */
	public function showRole($id) {
		$role = $this->accessService->showRole($id);

		return new AccessShowRoleResource($role);
	}

	public function store(AccessStoreData $data) {
		$role = $this->accessService->store($data);

		return new AccessStoreResource($role);
	}

	/**
	 * @throws ErrorMessageException
	 */
	public function update(AccessUpdateData $data, $id) {
		$role = $this->accessService->update($id, $data);

		return new AccessUpdateResource($role);
	}

	public function destroy($id) {
		$this->accessService->destroy($id);

		return new SuccessResource([]);
	}
}

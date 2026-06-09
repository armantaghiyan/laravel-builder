<?php

namespace App\Http\Controllers\Admin;

use App\Http\Data\Admin\Admin\AdminIndexData;
use App\Http\Data\Admin\Admin\AdminLoginData;
use App\Http\Data\Admin\Admin\AdminStoreData;
use App\Http\Data\Admin\Admin\AdminUpdateData;
use App\Http\Resources\Admin\Admin\AdminIndexResource;
use App\Http\Resources\Admin\Admin\AdminLoginResource;
use App\Http\Resources\Admin\Admin\AdminShowResource;
use App\Http\Resources\Admin\Admin\AdminStartResource;
use App\Http\Resources\Admin\Admin\AdminStoreResource;
use App\Http\Resources\Admin\Admin\AdminUpdateResource;
use App\Http\Resources\SuccessResource;
use App\Services\Domain\Common\Constants\Rk;
use App\Services\Domain\User\Access\Constants\Permissions;
use App\Services\Domain\User\Access\Services\AccessService;
use App\Services\Domain\User\Admin\Services\AdminService;
use App\Services\Infrastructure\Exceptions\ErrorMessageException;
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

		return new AdminIndexResource($items, $count);
	}

	public function store(AdminStoreData $data): AdminStoreResource {
		$item = $this->adminService->store($data);

		return new AdminStoreResource([Rk::ITEM => $item]);
	}

	public function show($id): AdminShowResource {
		$item = $this->adminService->show($id);
		$adminRoles = $this->accessService->getAdminRoles($id);
		$roles = $this->accessService->getAllRoles('admin');

		return new AdminShowResource($item, $adminRoles, $roles);
	}

	public function update(AdminUpdateData $data, $id): AdminUpdateResource {
		$item = $this->adminService->update($data, $id);

		return new AdminUpdateResource($item);
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


		return new AdminLoginResource($admin, $apiToken);
	}

	public function adminStart() {
		$admin = $this->adminService->profile();

		$permissions = $this->accessService->getPermissions('admin');
		$adminPermissions = $this->accessService->getUserPermissions('admin');

		return new AdminStartResource($admin, $permissions, $adminPermissions);
	}

	public function logout() {
		$this->adminService->logout();

		return new SuccessResource([]);
	}
}

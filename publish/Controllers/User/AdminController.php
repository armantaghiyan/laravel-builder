<?php

namespace App\Http\Controllers\User;

use App\Dto\User\Admin\AdminIndexData;
use App\Dto\User\Admin\AdminLoginData;
use App\Dto\User\Admin\AdminStoreData;
use App\Dto\User\Admin\AdminUpdateData;
use App\Exceptions\ErrorMessageException;
use App\Helpers\Permissions;
use App\Http\Resources\GlobalResources\SuccessResource;
use App\Http\Resources\User\Admin\AdminIndexResource;
use App\Http\Resources\User\Admin\AdminLoginResource;
use App\Http\Resources\User\Admin\AdminShowResource;
use App\Http\Resources\User\Admin\AdminStartResource;
use App\Http\Resources\User\Admin\AdminStoreResource;
use App\Http\Resources\User\Admin\AdminUpdateResource;
use App\Services\Domain\User\AccessService;
use App\Services\Domain\User\AdminService;
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

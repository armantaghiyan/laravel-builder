<?php

namespace App\Http\Controllers\Admin;

use App\Core\Application\Actions\Admin\AdminIndexAction;
use App\Core\Application\Actions\Admin\AdminLoginAction;
use App\Core\Application\Actions\Admin\AdminLogoutAction;
use App\Core\Application\Actions\Admin\AdminShowAction;
use App\Core\Application\Actions\Admin\AdminStartAction;
use App\Core\Application\Actions\Admin\AdminStoreAction;
use App\Core\Application\Actions\Admin\AdminUpdateAction;
use App\Core\Infrastructure\Exceptions\ErrorMessageException;
use App\Http\Constants\Permissions;
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
use App\Http\Resources\Rk;
use App\Http\Resources\SuccessResource;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Routing\Controller;

class AdminController extends Controller {

	public function __construct(
		private readonly AdminLoginAction   $loginAction,
		private readonly AdminLogoutAction  $logoutAction,
		private readonly AdminStartAction   $startAction,
		private readonly AdminIndexAction   $indexAction,
		private readonly AdminShowAction    $showAction,
		private readonly AdminStoreAction   $storeAction,
		private readonly AdminUpdateAction  $updateAction,
	) {
	}


	#[Middleware('permission:' . Permissions::ADMIN_INDEX)]
	public function index(AdminIndexData $data): AdminIndexResource {
		[$items, $count] = $this->indexAction->execute($data);

		return new AdminIndexResource($items, $count);
	}

	#[Middleware('permission:' . Permissions::ADMIN_STORE)]
	public function store(AdminStoreData $data): AdminStoreResource {
		$item = $this->storeAction->execute($data);

		return new AdminStoreResource($item);
	}

	#[Middleware('permission:' . Permissions::ADMIN_INDEX)]
	public function show(int $id): AdminShowResource {
		[$item, $roles, $adminRoles] = $this->showAction->execute($id);

		return new AdminShowResource($item, $roles, $adminRoles);
	}

	#[Middleware('permission:' . Permissions::ADMIN_UPDATE)]
	public function update(AdminUpdateData $data, int $id): AdminUpdateResource {
		$item = $this->updateAction->execute($data, $id);

		return new AdminUpdateResource($item);
	}

	/**
	 * @throws ErrorMessageException
	 */
	public function login(AdminLoginData $data): AdminLoginResource {
		[$admin, $apiToken] = $this->loginAction->execute($data);

		return new AdminLoginResource($admin, $apiToken);
	}

	/**
	 * @throws ErrorMessageException
	 */
	public function adminStart() {
		[$admin, $permissions, $adminPermissions] = $this->startAction->execute();

		return new AdminStartResource($admin, $permissions, $adminPermissions);
	}

	public function logout(): SuccessResource {
		$this->logoutAction->execute();

		return new SuccessResource([]);
	}
}

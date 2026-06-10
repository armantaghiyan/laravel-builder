<?php

namespace App\Http\Resources\Admin\Admin;

use App\Http\Resources\Admin\Access\PermissionResource;
use App\Http\Resources\ResponseManager;
use App\Services\Domain\Common\Constants\Rk;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminStartResource extends JsonResource {

	public function __construct(
		public $admin,
		public $permissions,
		public $adminPermissions,
	) {
		parent::__construct($admin);
	}

	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array {

		return (new ResponseManager())->cast([
			Rk::ADMIN => new AdminResource($this->admin),
			Rk::PERMISSIONS => PermissionResource::collection($this->permissions),
			Rk::ADMIN_PERMISSIONS => PermissionResource::collection($this->adminPermissions),
		]);
	}
}

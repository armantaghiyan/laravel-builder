<?php

namespace App\Services\Domain\User\Admin\Resources;

use App\Services\Domain\Common\Constants\Rk;
use App\Services\Domain\User\Access\Resources\PermissionResource;
use App\Services\Infrastructure\Http\ResponseManager;
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

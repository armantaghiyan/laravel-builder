<?php

namespace App\Services\Domain\User\Admin\Resources;

use App\Helpers\ResponseManager;
use App\Services\Domain\User\Access\Resources\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminShowResource extends JsonResource {

	use ResponseManager;

	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array {

		return $this->cast([
			RK_ITEM => new AdminResource($this[RK_ITEM]),
            RK_ROLES => RoleResource::collection($this[RK_ROLES]),
			RK_ADMIN_ROLES => RoleResource::collection($this[RK_ADMIN_ROLES]),
		]);
	}
}

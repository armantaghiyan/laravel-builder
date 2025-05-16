<?php

namespace App\Http\Resources\User\Admin;

use App\Http\Resources\Models\App\PermissionResource;
use App\Http\Resources\Models\User\AdminResource;
use App\Helpers\ResponseManager;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminStartResource extends JsonResource {

	use ResponseManager;

	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array {

		return $this->cast([
			RK_ADMIN => new AdminResource($this[RK_ADMIN]),
			RK_PERMISSIONS => PermissionResource::collection($this[RK_PERMISSIONS]),
		]);
	}
}

<?php

namespace App\Services\Domain\User\Admin\Resources;

use App\Services\Domain\Common\Constants\Rk;
use App\Services\Domain\User\Access\Resources\RoleResource;
use App\Services\Infrastructure\Http\ResponseManager;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminShowResource extends JsonResource {

	public function __construct(
		public $item,
		public $roles,
		public $admin_roles,
	) {
		parent::__construct($item);
	}

	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array {

		return (new ResponseManager())->cast([
			Rk::ITEM => new AdminResource($this->item),
			Rk::ROLES => RoleResource::collection($this->roles),
			Rk::ADMIN_ROLES => RoleResource::collection($this->admin_roles),
		]);
	}
}

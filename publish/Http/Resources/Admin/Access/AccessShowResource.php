<?php

namespace App\Http\Resources\Admin\Access;

use App\Http\Resources\ResponseManager;
use App\Services\Domain\Common\Constants\Rk;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccessShowResource extends JsonResource {

	public function __construct(
		public $permissions,
	) {
		parent::__construct($this->permissions);
	}

	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array {

		return (new ResponseManager())->cast([
			Rk::PERMISSIONS => PermissionResource::collection($this->permissions),
		]);
	}
}

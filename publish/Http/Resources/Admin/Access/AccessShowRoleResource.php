<?php

namespace App\Http\Resources\Admin\Access;

use App\Http\Resources\ResponseManager;
use App\Http\Resources\Rk;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccessShowRoleResource extends JsonResource {

	public function __construct(
		public $item,
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
			Rk::ITEM => new RoleResource($this->item),
		]);
	}
}

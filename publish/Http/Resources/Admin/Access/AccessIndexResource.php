<?php

namespace App\Http\Resources\Admin\Access;

use App\Core\Domain\Common\Constants\Rk;
use App\Http\Resources\ResponseManager;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccessIndexResource extends JsonResource {

	public function __construct(
		public $items,
	) {
		parent::__construct($this->items);
	}

	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array {

		return (new ResponseManager())->cast([
			Rk::ITEMS => RoleResource::collection($this->items),
		]);
	}
}

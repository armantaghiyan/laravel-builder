<?php

namespace App\Http\Resources\Admin\Admin;

use App\Http\Resources\ResponseManager;
use App\Http\Resources\Rk;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminIndexResource extends JsonResource {

	public function __construct(
		public $items,
		public $count,
	) {
		parent::__construct($items);
	}

	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array {

		return (new ResponseManager())->cast([
			Rk::ITEMS => AdminResource::collection($this->items),
			Rk::COUNT => $this->count,
		]);
	}
}

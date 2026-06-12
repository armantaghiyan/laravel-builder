<?php

namespace App\Http\Resources\Admin\Admin;

use App\Core\Domain\Common\Constants\Rk;
use App\Http\Resources\ResponseManager;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminUpdateResource extends JsonResource {

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
			Rk::ITEM => new AdminResource($this->item),
		]);
	}
}

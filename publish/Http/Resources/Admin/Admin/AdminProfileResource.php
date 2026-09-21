<?php

namespace App\Http\Resources\Admin\Admin;

use App\Http\Resources\ResponseManager;
use App\Http\Resources\Rk;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminProfileResource extends JsonResource {

	public function __construct(
		public $item,
	) {
		parent::__construct($item);
	}

	public function toArray(Request $request): array {
		return (new ResponseManager)->cast([
			Rk::ITEM => new AdminResource($this->item),
		]);
	}
}

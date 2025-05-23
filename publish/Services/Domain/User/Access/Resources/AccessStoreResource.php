<?php

namespace App\Services\Domain\User\Access\Resources;

use App\Services\Domain\Common\Constants\Rk;
use App\Services\Infrastructure\Http\ResponseManager;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccessStoreResource extends JsonResource {

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

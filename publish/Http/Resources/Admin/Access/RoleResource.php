<?php

namespace App\Http\Resources\Admin\Access;

use App\Core\Shared\Helper\DateHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource {

	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array {

		return [
			'id' => $this['id'],
			'name' => $this->whenHas('name', $this['name']),
			'created_at' => DateHelper::convert($this['created_at']),
			'updated_at' => DateHelper::convert($this['updated_at']),
		];
	}
}

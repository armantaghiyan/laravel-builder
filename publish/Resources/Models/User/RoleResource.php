<?php

namespace App\Http\Resources\Models\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

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
			'created_at' => $this->whenHas('created_at', Carbon::parse($this['created_at'])->format('Y-m-d H:i')),
			'updated_at' => $this->whenHas('updated_at', Carbon::parse($this['updated_at'])->format('Y-m-d H:i')),
		];
	}
}

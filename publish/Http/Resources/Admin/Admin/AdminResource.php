<?php

namespace App\Http\Resources\Admin\Admin;

use App\Core\Domain\Admin\Models\Admin;
use App\Core\Shared\Helper\DateHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource {

	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array {

		return [
			Admin::ID => $this[Admin::ID],
			Admin::NAME => $this->whenHas(Admin::NAME, $this[Admin::NAME]),
			Admin::USERNAME => $this->whenHas(Admin::USERNAME, $this[Admin::USERNAME]),
			Admin::IMAGE => $this->whenHas(Admin::IMAGE, $this[Admin::IMAGE]),

			Admin::LAST_LOGIN => $this->whenHas(Admin::LAST_LOGIN, DateHelper::convert($this[Admin::LAST_LOGIN])),
			Admin::CREATED_AT => $this->whenHas(Admin::CREATED_AT, DateHelper::convert($this[Admin::CREATED_AT])),
			Admin::UPDATED_AT => $this->whenHas(Admin::UPDATED_AT, DateHelper::convert($this[Admin::UPDATED_AT])),
		];
	}
}

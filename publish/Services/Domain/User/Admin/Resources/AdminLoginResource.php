<?php

namespace App\Services\Domain\User\Admin\Resources;

use App\Services\Domain\Common\Constants\Rk;
use App\Services\Infrastructure\Http\ResponseManager;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminLoginResource extends JsonResource {

	public function __construct(
		public $admin,
		public $apiToken,
	) {
		parent::__construct($admin);
	}

	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array {

		return (new ResponseManager())->cast([
			Rk::ADMIN => new AdminResource($this->admin),
			Rk::API_TOKEN => $this->apiToken,
		]);
	}
}

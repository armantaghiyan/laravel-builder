<?php

namespace App\Http\Resources\Admin\Admin;

use App\Core\Domain\Logger\Constants\LogEvent;
use App\Core\Domain\Logger\Constants\LogLevel;
use App\Core\Domain\Logger\Constants\LogLoggableType;
use App\Core\Domain\Logger\Constants\LogUserGuard;
use App\Http\Resources\Admin\Access\PermissionResource;
use App\Http\Resources\ResponseManager;
use App\Http\Resources\Rk;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminStartResource extends JsonResource {

	public function __construct(
		public $admin,
		public $permissions,
		public $adminPermissions,
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
			Rk::PERMISSIONS => PermissionResource::collection($this->permissions),
			Rk::ADMIN_PERMISSIONS => PermissionResource::collection($this->adminPermissions),
			Rk::ENUMS => [
				'log_levels' => LogLevel::options(),
				'log_user_guard' => LogUserGuard::options(),
				'log_event' => LogEvent::options(),
				'log_loggable_type' => LogLoggableType::options(),
			],
		]);
	}
}

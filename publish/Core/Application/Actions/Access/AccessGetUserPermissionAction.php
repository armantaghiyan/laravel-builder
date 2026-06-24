<?php

namespace App\Core\Application\Actions\Access;

use App\Core\Domain\Access\Repositories\AccessRepository;
use App\Core\Infrastructure\Exceptions\ErrorMessageException;
use Illuminate\Support\Facades\Auth;

readonly class AccessGetUserPermissionAction {

	public function __construct(
	) {
	}

	/**
	 * @throws ErrorMessageException
	 */
	public function execute(string $guard) {
		$admin = Auth::guard($guard)->user();
		if (!$admin) return [];

		return $admin->getAllPermissions();
	}
}

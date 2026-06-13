<?php

namespace App\Core\Application\Actions\Access;

use App\Core\Domain\Access\Repositories\AccessRepository;
use App\Core\Infrastructure\Exceptions\ErrorMessageException;
use Illuminate\Support\Facades\Auth;

readonly class AccessGetUserPermissionAction {

    public function __construct(
        private AccessRepository $repository
    ) {
    }

    /**
     * @throws ErrorMessageException
     */
    public function execute(string $guard) {
        $admin = Auth::guard($guard)->user();
        if (!$admin) return [];

        if ($admin->hasRole('Super Admin')) {
            return $this->repository->getPermissions($guard);
        }

        return $admin->getAllPermissions();
    }
}

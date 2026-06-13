<?php

namespace App\Core\Application\Actions\Access;

use App\Core\Domain\Access\Repositories\AccessRepository;
use App\Core\Infrastructure\Exceptions\ErrorMessageException;
use Illuminate\Support\Facades\Auth;

readonly class AccessGetPermissionAction {

    public function __construct(
        private AccessRepository $repository
    ) {
    }


    /**
     * @throws ErrorMessageException
     */
    public function execute(string $guard): mixed {
        $admin = Auth::guard($guard)->user();
        return $admin ? $this->repository->getPermissions($guard) : [];
    }
}

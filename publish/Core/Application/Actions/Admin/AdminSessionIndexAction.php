<?php

namespace App\Core\Application\Actions\Admin;

use App\Core\Domain\Admin\Repositories\AdminRepository;
use App\Core\Infrastructure\Auth\AuthManger;
use Illuminate\Database\Eloquent\Collection;

readonly class AdminSessionIndexAction
{
    public function __construct(
        private AdminRepository $adminRepository,
        private AuthManger $authService,
    ) {}

    public function execute(?int $adminId = null): Collection
    {
        $admin = $adminId === null
            ? $this->authService->currentAdmin()
            : $this->adminRepository->findById($adminId);

        return $this->adminRepository->activeSessions($admin);
    }
}

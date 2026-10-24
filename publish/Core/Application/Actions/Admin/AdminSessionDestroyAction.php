<?php

namespace App\Core\Application\Actions\Admin;

use App\Core\Domain\Admin\Repositories\AdminRepository;
use App\Core\Domain\Common\Constants\StatusCodes;
use App\Core\Infrastructure\Auth\AuthManger;
use App\Core\Infrastructure\Exceptions\ErrorMessageException;

readonly class AdminSessionDestroyAction
{
    public function __construct(
        private AdminRepository $adminRepository,
        private AuthManger $authService,
    ) {}

    public function execute(int $id, ?int $adminId = null): void
    {
        $currentAdmin = $this->authService->currentAdmin();
        $admin = $adminId === null ? $currentAdmin : $this->adminRepository->findById($adminId);
        $session = $this->adminRepository->findSessionById($admin, $id);

        if ($session->getKey() === $currentAdmin->currentAccessToken()?->getKey()) {
            throw new ErrorMessageException(__('error.current_session_cannot_be_deleted'), StatusCodes::Bad_request);
        }

        $this->adminRepository->revokeSession($session);
    }
}

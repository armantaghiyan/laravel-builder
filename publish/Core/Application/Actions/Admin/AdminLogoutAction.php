<?php

namespace App\Core\Application\Actions\Admin;

use App\Core\Infrastructure\Auth\AuthManger;

readonly class AdminLogoutAction {

    public function __construct(
        private AuthManger $authService,
    ) {
    }

    public function execute(): void {
        $this->authService->logoutAdmin();
    }
}

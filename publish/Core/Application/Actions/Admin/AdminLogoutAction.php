<?php

namespace App\Core\Application\Actions\Admin;

use App\Core\Domain\Admin\Models\Admin;
use App\Core\Domain\Logger\Constants\LogEvent;
use App\Core\Infrastructure\Auth\AuthManger;
use App\Core\Infrastructure\Services\Logger;

readonly class AdminLogoutAction {

    public function __construct(
        private AuthManger $authService,
        private Logger     $logger,
    ) {
    }

    public function execute(): void {
        $admin = $this->authService->currentAdmin();

        $this->logger->log(
            LogEvent::AdminLoggedOut,
            "مدیر «{$admin[Admin::NAME]}» (شناسه: {$admin[Admin::ID]}) از سامانه خارج شد.",
            $admin,
        );

        $this->authService->logoutAdmin();
    }
}

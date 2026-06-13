<?php

namespace App\Core\Application\Actions\Admin;

use App\Core\Domain\Admin\Models\Admin;
use App\Core\Infrastructure\Auth\AuthManger;

readonly class AdminProfileAction {

    public function __construct(
        private AuthManger $authService,
    ) {
    }

    public function execute(): ?Admin {
        return $this->authService->currentAdmin();
    }
}

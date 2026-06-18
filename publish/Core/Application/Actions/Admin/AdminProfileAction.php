<?php

namespace App\Core\Application\Actions\Admin;

use App\Core\Domain\Admin\Models\Faq;
use App\Core\Infrastructure\Auth\AuthManger;

readonly class AdminProfileAction {

    public function __construct(
        private AuthManger $authService,
    ) {
    }

    public function execute(): ?Faq {
        return $this->authService->currentAdmin();
    }
}

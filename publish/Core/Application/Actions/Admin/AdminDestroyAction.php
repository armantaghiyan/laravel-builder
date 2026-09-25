<?php

namespace App\Core\Application\Actions\Admin;

use App\Core\Domain\Admin\Models\Admin;
use App\Core\Domain\Admin\Repositories\AdminRepository;
use App\Core\Domain\Logger\Constants\LogEvent;
use App\Core\Infrastructure\Services\Logger;

readonly class AdminDestroyAction {

    public function __construct(
        private AdminRepository $adminRepository,
        private Logger          $logger,
    ) {
    }

    public function execute(int $id): void {
        $admin = $this->adminRepository->findById($id);
        $this->adminRepository->delete($admin);

        $this->logger->log(
            LogEvent::AdminDeleted,
            "مدیر «{$admin[Admin::NAME]}» (شناسه: {$admin[Admin::ID]}) حذف شد.",
            $admin,
        );
    }
}

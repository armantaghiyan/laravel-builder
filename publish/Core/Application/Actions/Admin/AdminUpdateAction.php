<?php

namespace App\Core\Application\Actions\Admin;

use App\Core\Domain\Admin\Models\Admin;
use App\Core\Domain\Admin\Repositories\AdminRepository;
use App\Core\Domain\Logger\Constants\LogEvent;
use App\Core\Infrastructure\Services\Logger;
use App\Http\Data\Admin\Admin\AdminUpdateData;

readonly class AdminUpdateAction {

    public function __construct(
        private AdminRepository $adminRepository,
        private Logger          $logger,
    ) {
    }

    public function execute(AdminUpdateData $data, int $id): Admin {
        $admin = $this->adminRepository->findById($id);

        $admin = $this->adminRepository->update($admin, [
            Admin::NAME => $data->name,
            Admin::USERNAME => $data->username,
        ]);

        $this->logger->log(
            LogEvent::AdminUpdated,
            "اطلاعات مدیر «{$admin[Admin::NAME]}» (شناسه: {$admin[Admin::ID]}) به‌روزرسانی شد.",
            $admin,
        );

        return $admin;
    }
}

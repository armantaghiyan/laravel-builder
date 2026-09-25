<?php

namespace App\Core\Application\Actions\Admin;

use App\Core\Domain\Admin\Models\Admin;
use App\Core\Domain\Admin\Repositories\AdminRepository;
use App\Core\Domain\Logger\Constants\LogEvent;
use App\Core\Infrastructure\Services\Logger;
use App\Http\Data\Admin\Admin\AdminStoreData;
use Illuminate\Support\Facades\Hash;

readonly class AdminStoreAction {

    public function __construct(
        private AdminRepository $adminRepository,
        private Logger          $logger,
    ) {
    }

    public function execute(AdminStoreData $data): Admin {
        $admin = $this->adminRepository->create([
            Admin::NAME => $data->name,
            Admin::USERNAME => $data->username,
            Admin::PASSWORD => Hash::make($data->password),
        ]);

        $this->logger->log(
            LogEvent::AdminCreated,
            "مدیر «{$admin[Admin::NAME]}» (شناسه: {$admin[Admin::ID]}) ایجاد شد.",
            $admin,
        );

        return $admin;
    }
}

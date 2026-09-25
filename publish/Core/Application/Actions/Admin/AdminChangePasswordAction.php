<?php

namespace App\Core\Application\Actions\Admin;

use App\Core\Domain\Admin\Models\Admin;
use App\Core\Domain\Admin\Repositories\AdminRepository;
use App\Core\Domain\Common\Constants\StatusCodes;
use App\Core\Domain\Logger\Constants\LogEvent;
use App\Core\Infrastructure\Auth\AuthManger;
use App\Core\Infrastructure\Exceptions\ErrorMessageException;
use App\Core\Infrastructure\Services\Logger;
use App\Http\Data\Admin\Admin\AdminChangePasswordData;
use Illuminate\Support\Facades\Hash;

readonly class AdminChangePasswordAction {

    public function __construct(
        private AdminRepository $adminRepository,
        private AuthManger      $authService,
        private Logger          $logger,
    ) {
    }

    /**
     * @throws ErrorMessageException
     */
    public function execute(AdminChangePasswordData $data): void {
        $admin = $this->authService->currentAdmin();

        if (!Hash::check($data->old_password, $admin[Admin::PASSWORD])) {
            throw new ErrorMessageException(__('error.old_password_incorrect'), StatusCodes::Bad_request);
        }

        $this->adminRepository->updateField($admin, [
            Admin::PASSWORD => Hash::make($data->new_password),
        ]);

        $this->logger->log(
            LogEvent::AdminPasswordChanged,
            "رمز عبور مدیر «{$admin[Admin::NAME]}» (شناسه: {$admin[Admin::ID]}) تغییر داده شد.",
            $admin,
        );
    }
}

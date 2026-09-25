<?php

namespace App\Core\Application\Actions\Admin;

use App\Core\Domain\Admin\Models\Admin;
use App\Core\Domain\Admin\Repositories\AdminRepository;
use App\Core\Domain\Common\Constants\StatusCodes;
use App\Core\Domain\Logger\Constants\LogEvent;
use App\Core\Infrastructure\Exceptions\ErrorMessageException;
use App\Core\Infrastructure\Services\Logger;
use App\Http\Data\Admin\Admin\AdminLoginData;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

readonly class AdminLoginAction {

    public function __construct(
        private AdminRepository $adminRepository,
        private Logger          $logger,
    ) {
    }

    /**
     * @throws ErrorMessageException
     */
    public function execute(AdminLoginData $data): array {
        $admin = $this->adminRepository->findByUsername($data->username);

        if (!$admin || !Hash::check($data->password, $admin[Admin::PASSWORD])) {
            throw new ErrorMessageException(__('error.password_incorrect'), StatusCodes::Bad_request);
        }

        $token = $admin->createToken("ADMIN TOKEN", ['*'], Carbon::now()->addWeek())->plainTextToken;

        $this->adminRepository->updateField($admin, [
            Admin::LAST_LOGIN => Carbon::now()
        ]);

        $this->logger->log(
            LogEvent::AdminLoggedIn,
            "مدیر «{$admin[Admin::NAME]}» (شناسه: {$admin[Admin::ID]}) وارد سامانه شد.",
            $admin,
        );

        return [$admin, $token];
    }
}

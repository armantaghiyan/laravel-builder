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
use Illuminate\Support\Facades\Log;

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
        $passwordHash = $admin?->getAuthPassword();

        if (!$passwordHash) {
            // Missing accounts/passwords must also perform the configured hashing work.
            Hash::make('invalid-admin-login');
        }

        if (!$admin || !$passwordHash || !Hash::check($data->password, $passwordHash)) {
            Log::warning('Admin login failed', [
                'ip' => request()->ip(),
                'username_hash' => hash('sha256', $data->username),
            ]);

            throw new ErrorMessageException(__('error.password_incorrect'), StatusCodes::Bad_request);
        }

        $token = $admin->createToken('ADMIN TOKEN', ['*'], Carbon::now()->addWeek())->plainTextToken;

        $this->adminRepository->updateField($admin, [
            Admin::LAST_LOGIN => Carbon::now(),
        ]);

        $this->logger->log(
            LogEvent::AdminLoggedIn,
            "مدیر «{$admin[Admin::NAME]}» (شناسه: {$admin[Admin::ID]}) وارد سامانه شد.",
            $admin,
        );

        return [$admin, $token];
    }
}

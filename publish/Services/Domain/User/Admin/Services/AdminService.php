<?php

namespace App\Services\Domain\User\Admin\Services;

use App\Services\Domain\Common\Constants\StatusCodes;
use App\Services\Domain\User\Admin\Dto\AdminChangePasswordData;
use App\Services\Domain\User\Admin\Dto\AdminIndexData;
use App\Services\Domain\User\Admin\Dto\AdminLoginData;
use App\Services\Domain\User\Admin\Dto\AdminStoreData;
use App\Services\Domain\User\Admin\Dto\AdminUpdateData;
use App\Services\Domain\User\Admin\Models\Admin;
use App\Services\Domain\User\Admin\Repositories\AdminRepository;
use App\Services\Infrastructure\Auth\AuthManger;
use App\Services\Infrastructure\Exceptions\ErrorMessageException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class AdminService {

    public function __construct(
        private readonly AdminRepository $adminRepository,
        private readonly AuthManger      $authService,
    ) {
    }

    /**
     * @throws ErrorMessageException
     */
    public function login(AdminLoginData $data): array {
        $admin = $this->adminRepository->findByUsername($data->username);

        if (!$admin || !Hash::check($data->password, $admin[Admin::PASSWORD])) {
            throw new ErrorMessageException(__('error.password_incorrect'), StatusCodes::Bad_request);
        }

        $token = $admin->createToken("ADMIN TOKEN", ['*'], Carbon::now()->addWeek())->plainTextToken;

        $this->adminRepository->updateField($admin, [
            Admin::LAST_LOGIN => Carbon::now()
        ]);

        return [$admin, $token];
    }

    public function logout(): void {
        $this->authService->logoutAdmin();
    }

    /**
     * @throws ErrorMessageException
     */
    public function changePassword(AdminChangePasswordData $data): void {
        $admin = $this->authService->currentAdmin();

        if (!Hash::check($data->old_password, $admin[Admin::PASSWORD])) {
            throw new ErrorMessageException(__('error.old_password_incorrect'), StatusCodes::Bad_request);
        }

        $this->adminRepository->updateField($admin, [
            Admin::PASSWORD => Hash::make($data->new_password)
        ]);
    }

    public function profile() {
        return $this->authService->currentAdmin();
    }

    public function index(AdminIndexData $data): array {
        return $this->adminRepository->index($data);
    }

    public function show(int $id): Admin {
        return $this->adminRepository->findById($id);
    }

    public function store(AdminStoreData $data): Admin {
        return $this->adminRepository->create([
            Admin::NAME => $data->name,
            Admin::USERNAME => $data->username,
            Admin::PASSWORD => $data->password,
        ]);
    }

    public function update(AdminUpdateData $data, int $id): Admin {
        $admin = $this->adminRepository->findById($id);

        return $this->adminRepository->update($admin, [
            Admin::NAME => $data->name,
            Admin::USERNAME => $data->username,
        ]);
    }

    public function destroy(int $id): void {
        $admin = $this->adminRepository->findById($id);
        $this->adminRepository->delete($admin);
    }
}

<?php

namespace App\Core\Domain\Admin\Services;

use App\Core\Domain\Admin\Models\Admin;
use App\Core\Domain\Admin\Repositories\AdminRepository;
use App\Core\Domain\Common\Constants\StatusCodes;
use App\Core\Infrastructure\Auth\AuthManger;
use App\Core\Infrastructure\Exceptions\ErrorMessageException;
use App\Http\Data\Admin\Admin\AdminChangePasswordData;
use App\Http\Data\Admin\Admin\AdminIndexData;
use App\Http\Data\Admin\Admin\AdminLoginData;
use App\Http\Data\Admin\Admin\AdminStoreData;
use App\Http\Data\Admin\Admin\AdminUpdateData;
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

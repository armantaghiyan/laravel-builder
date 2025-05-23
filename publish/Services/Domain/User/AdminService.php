<?php

namespace App\Services\Domain\User;

use App\Dto\User\Admin\AdminChangePasswordData;
use App\Dto\User\Admin\AdminIndexData;
use App\Dto\User\Admin\AdminLoginData;
use App\Dto\User\Admin\AdminStoreData;
use App\Dto\User\Admin\AdminUpdateData;
use App\Exceptions\ErrorMessageException;
use App\Helpers\StatusCodes;
use App\Models\User\Admin;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use User\AdminRepository;

class AdminService {

    public function __construct(
        private readonly AdminRepository $adminRepository,
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
        $admin = Auth::guard('admin')->user();
        $admin->currentAccessToken()->delete();
    }

    /**
     * @throws ErrorMessageException
     */
    public function changePassword(AdminChangePasswordData $data): void {
        $admin = Auth::guard('admin')->user();

        if (!Hash::check($data->old_password, $admin[Admin::PASSWORD])) {
            throw new ErrorMessageException(__('error.old_password_incorrect'), StatusCodes::Bad_request);
        }

        $this->adminRepository->updateField($admin, [
            Admin::PASSWORD => Hash::make($data->new_password)
        ]);
    }

    public function profile() {
        return Auth::guard('admin')->user();
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

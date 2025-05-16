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
use App\Repositories\User\AdminRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminService {

	public function __construct(
		private AdminRepository $adminRepository,
	) {
	}

	/**
	 * @throws ErrorMessageException
	 */
	public function login(AdminLoginData $data): array {
		$admin = Admin::where(Admin::USERNAME, $data->username)
			->first([Admin::ID, Admin::NAME, Admin::USERNAME, Admin::IMAGE, Admin::PASSWORD]);


		if (!$admin || !Hash::check($data->password, $admin[Admin::PASSWORD])) {
			throw new ErrorMessageException(__('error.password_incorrect'), StatusCodes::Bad_request);
		}

		$token = $admin->createToken("ADMIN TOKEN", ['*'], Carbon::now()->addWeek())->plainTextToken;

		$admin[Admin::LAST_LOGIN] = Carbon::now();
		$admin->save();

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

		$admin[Admin::PASSWORD] = Hash::make($data->new_password);
		$admin->save();
	}

	public function profile() {
		return Auth::guard('admin')->user();
	}

	public function index(AdminIndexData $data): array {
		return $this->adminRepository->index($data);
	}

	public function show($id): Admin {
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
		$item = $this->adminRepository->findById($id);

		return $this->adminRepository->update($item, [
			Admin::NAME => $data->name,
			Admin::USERNAME => $data->username,
		]);
	}

	public function destroy(int $id): void {
		$item = $this->adminRepository->findById($id);
		$this->adminRepository->delete($item);
	}
}

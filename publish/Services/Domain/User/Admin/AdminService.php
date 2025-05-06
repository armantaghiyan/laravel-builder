<?php

namespace App\Services\Domain\User\Admin;

use App\Dto\User\Admin\AdminChangePasswordData;
use App\Dto\User\Admin\AdminLoginData;
use App\Dto\User\Admin\AdminStoreData;
use App\Dto\User\Admin\AdminUpdateData;
use App\Exceptions\ErrorMessageException;
use App\Helpers\StatusCodes;
use App\Models\User\Admin;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminService {

	/**
	 * @throws ErrorMessageException
	 */
	public function login(AdminLoginData $data): array {
		$admin = Admin::where(COL_ADMIN_USERNAME, $data->username)
			->first([COL_ADMIN_ID, COL_ADMIN_NAME, COL_ADMIN_USERNAME, COL_ADMIN_IMAGE, COL_ADMIN_PASSWORD]);

		if (!$admin || !Hash::check($data->password, $admin[COL_ADMIN_PASSWORD])) {
			throw new ErrorMessageException(__('error.password_incorrect'), StatusCodes::Bad_request);
		}

		$token = $admin->createToken("ADMIN TOKEN", ['*'], Carbon::now()->addWeek())->plainTextToken;

		$admin[COL_ADMIN_LAST_LOGIN] = Carbon::now();
		$admin->save();

		return [$admin, $token];
	}

	public function logout(): void {
		$admin = Auth::guard('admin')->user();
		$admin->currentAccessToken()->delete();
	}

	public function store(AdminStoreData $data): Admin {
		$admin = new Admin();
		$admin[COL_ADMIN_NAME] = $data->name;
		$admin[COL_ADMIN_USERNAME] = $data->username;
		$admin[COL_ADMIN_PASSWORD] = Hash::make($data->password);
		$admin[COL_ADMIN_CREATED_AT] = Carbon::now();
		$admin->save();

		return $admin;
	}

	/**
	 * @throws ErrorMessageException
	 */
	public function update(string $id, AdminUpdateData $data): Admin {
		$admin = Admin::where(COL_ADMIN_ID, $id)->firstOrError();

		$existing = Admin::where(COL_ADMIN_USERNAME, $data->username)->first();
		if ($existing && $existing[COL_ADMIN_ID] !== $admin[COL_ADMIN_ID]) {
			throw new ErrorMessageException(__('error.username_already_registered'), StatusCodes::Bad_request);
		}

		$admin[COL_ADMIN_NAME] = $data->name;
		$admin[COL_ADMIN_USERNAME] = $data->username;
		$admin->save();

		return $admin;
	}

	/**
	 * @throws ErrorMessageException
	 */
	public function changePassword(AdminChangePasswordData $data): void {
		$admin = Auth::guard('admin')->user();

		if (!Hash::check($data->old_password, $admin[COL_ADMIN_PASSWORD])) {
			throw new ErrorMessageException(__('error.old_password_incorrect'), StatusCodes::Bad_request);
		}

		$admin[COL_ADMIN_PASSWORD] = Hash::make($data->new_password);
		$admin->save();
	}

	public function show(string $id): Admin {
		return Admin::where(COL_ADMIN_ID, $id)->firstOrError();
	}

	public function profile() {
		return Auth::guard('admin')->user();
	}
}

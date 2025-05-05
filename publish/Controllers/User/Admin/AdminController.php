<?php

namespace App\Http\Controllers\User\Admin;

use App\Dto\User\Admin\AdminLoginData;
use App\Exceptions\ErrorMessageException;
use App\Http\Controllers\Controller;
use App\Http\Resources\GlobalResources\SuccessResource;
use App\Http\Resources\User\Admin\AdminLoginResource;
use App\Http\Resources\User\Admin\AdminStartResource;
use App\Services\Domain\User\Admin\AdminService;

class AdminController extends Controller {

    public function __construct(
        private AdminService $adminService
    ) {

    }

    /**
     * @throws ErrorMessageException
     */
    public function login(AdminLoginData $data) {
        [$admin, $apiToken] = $this->adminService->login($data);


        return new AdminLoginResource([
            RK_API_TOKEN => $apiToken,
            RK_ADMIN => $admin,
        ]);
    }

    public function adminStart() {
        $admin = $this->adminService->profile();

        return new AdminStartResource([
            RK_ADMIN => $admin,
        ]);
    }

    public function logout() {
        $this->adminService->logout();

        return new SuccessResource([]);
    }
}

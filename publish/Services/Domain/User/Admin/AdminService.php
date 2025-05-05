<?php

namespace App\Services\Domain\User\Admin;

use App\Dto\User\Admin\AdminChangePasswordData;
use App\Dto\User\Admin\AdminLoginData;
use App\Dto\User\Admin\AdminStoreData;
use App\Dto\User\Admin\AdminUpdateData;
use App\Models\User\Admin;

class AdminService {

    public function __construct(
        private AdminQueryService $adminQueryService,
        private AdminCommandService $adminCommandService,
    ) {
    }

    public function login(AdminLoginData $data): array {
        return $this->adminCommandService->login($data);
    }

    public function logout(): void {
        $this->adminCommandService->logout();
    }

    public function store(AdminStoreData $data): Admin {
        return $this->adminCommandService->store($data);
    }

    public function show(string $id): Admin {
        return $this->adminQueryService->show($id);
    }

    public function update(string $id, AdminUpdateData $data): Admin {
        return $this->adminCommandService->update($id, $data);
    }

    public function changePassword(AdminChangePasswordData $data): void {
        $this->adminCommandService->changePassword($data);
    }

    public function profile() {
        return $this->adminQueryService->profile();
    }
}

<?php

namespace App\Core\Application\Actions\Admin;

use App\Core\Domain\Admin\Models\Admin;
use App\Core\Domain\Admin\Repositories\AdminRepository;
use App\Http\Data\Admin\Admin\AdminUpdateData;

readonly class AdminUpdateAction {

    public function __construct(
        private AdminRepository $adminRepository,
    ) {
    }

    public function execute(AdminUpdateData $data, int $id): Admin {
        $admin = $this->adminRepository->findById($id);

        return $this->adminRepository->update($admin, [
            Admin::NAME => $data->name,
            Admin::USERNAME => $data->username,
        ]);
    }
}

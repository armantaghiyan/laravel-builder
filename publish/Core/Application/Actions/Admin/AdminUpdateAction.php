<?php

namespace App\Core\Application\Actions\Admin;

use App\Core\Domain\Admin\Models\Faq;
use App\Core\Domain\Admin\Repositories\AdminRepository;
use App\Http\Data\Admin\Admin\AdminUpdateData;

readonly class AdminUpdateAction {

    public function __construct(
        private AdminRepository $adminRepository,
    ) {
    }

    public function execute(AdminUpdateData $data, int $id): Faq {
        $admin = $this->adminRepository->findById($id);

        return $this->adminRepository->update($admin, [
            Faq::NAME => $data->name,
            Faq::USERNAME => $data->username,
        ]);
    }
}

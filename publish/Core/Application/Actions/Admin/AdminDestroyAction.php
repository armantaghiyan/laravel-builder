<?php

namespace App\Core\Application\Actions\Admin;

use App\Core\Domain\Admin\Repositories\AdminRepository;

readonly class AdminDestroyAction {

    public function __construct(
        private AdminRepository $adminRepository,
    ) {
    }

    public function execute(int $id): void {
        $admin = $this->adminRepository->findById($id);
        $this->adminRepository->delete($admin);
    }
}

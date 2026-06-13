<?php

namespace App\Core\Application\Actions\Admin;

use App\Core\Domain\Admin\Repositories\AdminRepository;
use App\Http\Data\Admin\Admin\AdminIndexData;

readonly class AdminIndexAction {

    public function __construct(
        private AdminRepository $adminRepository,
    ) {
    }

    public function execute(AdminIndexData $data): array {
        return $this->adminRepository->index($data);
    }
}

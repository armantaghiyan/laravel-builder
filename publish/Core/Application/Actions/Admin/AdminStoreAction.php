<?php

namespace App\Core\Application\Actions\Admin;

use App\Core\Domain\Admin\Models\Admin;
use App\Core\Domain\Admin\Repositories\AdminRepository;
use App\Http\Data\Admin\Admin\AdminStoreData;
use Illuminate\Support\Facades\Hash;

readonly class AdminStoreAction {

    public function __construct(
        private AdminRepository $adminRepository,
    ) {
    }

    public function execute(AdminStoreData $data): Admin {
        return $this->adminRepository->create([
            Admin::NAME => $data->name,
            Admin::USERNAME => $data->username,
            Admin::PASSWORD => Hash::make($data->password),
        ]);
    }
}

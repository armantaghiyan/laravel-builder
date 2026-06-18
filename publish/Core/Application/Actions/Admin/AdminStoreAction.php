<?php

namespace App\Core\Application\Actions\Admin;

use App\Core\Domain\Admin\Models\Faq;
use App\Core\Domain\Admin\Repositories\AdminRepository;
use App\Http\Data\Admin\Admin\AdminStoreData;
use Illuminate\Support\Facades\Hash;

readonly class AdminStoreAction {

    public function __construct(
        private AdminRepository $adminRepository,
    ) {
    }

    public function execute(AdminStoreData $data): Faq {
        return $this->adminRepository->create([
            Faq::NAME => $data->name,
            Faq::USERNAME => $data->username,
            Faq::PASSWORD => Hash::make($data->password),
        ]);
    }
}

<?php

namespace App\Services\Domain\User\Admin;

use App\Models\User\Admin;
use Illuminate\Support\Facades\Auth;

class AdminQueryService {

    public function show(string $id): Admin {
        return Admin::where(COL_ADMIN_ID, $id)->firstOrError();
    }

    public function profile() {
        return Auth::guard('admin')->user();
    }
}

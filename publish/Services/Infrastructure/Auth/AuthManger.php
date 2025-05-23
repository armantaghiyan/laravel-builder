<?php

namespace App\Services\Infrastructure\Auth;

use Illuminate\Support\Facades\Auth;

class AuthManger {

    public function currentAdmin() {
        return Auth::guard('admin')->user();
    }

    public function logoutAdmin(): void {
        $this->currentAdmin()->currentAccessToken()->delete();
    }
}

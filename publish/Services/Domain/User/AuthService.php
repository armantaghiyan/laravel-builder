<?php

namespace App\Services\Domain\User;

use Illuminate\Support\Facades\Auth;

class AuthService {

    public function currentAdmin() {
        return Auth::guard('admin')->user();
    }

    public function logoutAdmin(): void {
        $this->currentAdmin()->currentAccessToken()->delete();
    }
}

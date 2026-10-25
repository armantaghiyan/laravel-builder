<?php

namespace App\Core\Domain\Admin\Repositories;

use Illuminate\Support\Facades\Cache;
use RuntimeException;

class AdminLoginCaptchaRepository {

    public const LIFETIME = 120;

    public function store(string $id, string $answer, string $ip): void {
        if (!Cache::put($this->key($id), $this->digest($id, $answer, $ip), self::LIFETIME)) {
            throw new RuntimeException('Unable to store the login captcha.');
        }
    }

    public function consume(string $id, string $answer, string $ip): bool {
        $key = $this->key($id);

        // The lock makes removal and verification one operation across workers.
        return Cache::lock($key . ':lock', 5)->get(function () use ($key, $id, $answer, $ip): bool {
                $digest = Cache::pull($key);

                return is_string($digest) && hash_equals($digest, $this->digest($id, $answer, $ip));
            }) === true;
    }

    private function key(string $id): string {
        return 'admin-login-captcha:' . $id;
    }

    private function digest(string $id, string $answer, string $ip): string {
        return hash_hmac('sha256', $id . '|' . $ip . '|' . strtoupper($answer), (string)config('app.key'));
    }
}

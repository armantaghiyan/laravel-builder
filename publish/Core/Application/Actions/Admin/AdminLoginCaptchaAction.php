<?php

namespace App\Core\Application\Actions\Admin;

use App\Core\Domain\Admin\Repositories\AdminLoginCaptchaRepository;
use App\Core\Infrastructure\Services\CaptchaImage;
use Illuminate\Support\Str;

readonly class AdminLoginCaptchaAction {

    public function __construct(
        private AdminLoginCaptchaRepository $captchas,
        private CaptchaImage                $image,
    ) {
    }

    public function execute(string $ip): array {
        $alphabet = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $answer = '';

        for ($i = 0; $i < 6; $i++) {
            $answer .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }

        $id = (string)Str::uuid();
        $image = $this->image->render($answer);
        $this->captchas->store($id, $answer, $ip);

        return ['captcha_id' => $id, 'image' => $image, 'expires_in' => AdminLoginCaptchaRepository::LIFETIME];
    }
}

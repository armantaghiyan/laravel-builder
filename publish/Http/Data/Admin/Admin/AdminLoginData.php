<?php

namespace App\Http\Data\Admin\Admin;

use App\Core\Domain\Admin\Repositories\AdminLoginCaptchaRepository;
use App\Http\Data\WithApiValidator;
use App\Http\Rules\AdminLoginCaptchaRule;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Attributes\Validation\Bail;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\NotRegex;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Size;
use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Data;

#[MergeValidationRules]
class AdminLoginData extends Data {

    use WithApiValidator;

    public function __construct(
        #[Required, Min(3), Max(40)]
        public string $username,

        #[Required, Min(6), Max(40), NotRegex('/\x00/')]
        #[\SensitiveParameter]
        public string $password,

        #[Required, Uuid]
        public string $captcha_id,

        #[Bail, Required, Size(6)]
        public string $captcha,
    ) {
    }

    public static function rules(AdminLoginCaptchaRepository $captchas): array {
        return ['captcha' => [new AdminLoginCaptchaRule($captchas)]];
    }
}

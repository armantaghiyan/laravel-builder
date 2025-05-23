<?php

namespace App\Services\Domain\User\Admin\Dto;

use App\Services\Infrastructure\Dto\WithApiValidator;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class AdminLoginData extends Data {

    use WithApiValidator;

    #[Required, Min(3), Max(40)]
    public string $username;

    #[Required, Min(6), Max(40)]
    public string $password;
}

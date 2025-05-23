<?php

namespace App\Services\Domain\User\Admin\Dto;

use App\Services\Infrastructure\Dto\WithApiValidator;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class AdminChangePasswordData extends Data {

    use WithApiValidator;

    #[Required, Min(6), Max(40)]
    public string $old_password;

    #[Required, Min(6), Max(40)]
    public string $new_password;
}

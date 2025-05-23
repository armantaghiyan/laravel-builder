<?php

namespace App\Services\Domain\User\Admin\Dto;

use App\Services\Infrastructure\Dto\WithApiValidator;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class AdminUpdateData extends Data {

    use WithApiValidator;

    #[Required, Min(3), Max(40)]
    public string $name;

    #[Required, Min(3), Max(40)]
    public string $username;
}

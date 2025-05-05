<?php

namespace App\Dto\User\Admin;

use App\Dto\GlobalDto\WithApiValidator;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Max;

class AdminUpdateData extends Data {

    use WithApiValidator;

    #[Required, Min(3), Max(40)]
    public string $name;

    #[Required, Min(3), Max(40)]
    public string $username;
}

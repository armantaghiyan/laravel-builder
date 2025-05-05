<?php

namespace App\Dto\User\Admin;

use App\Dto\GlobalDto\WithApiValidator;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Max;

class AdminChangePasswordData extends Data {

    use WithApiValidator;

    #[Required, Min(6), Max(40)]
    public string $old_password;

    #[Required, Min(6), Max(40)]
    public string $new_password;
}

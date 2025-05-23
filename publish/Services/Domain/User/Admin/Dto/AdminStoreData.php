<?php

namespace App\Services\Domain\User\Admin\Dto;

use App\Services\Infrastructure\Dto\WithApiValidator;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

class AdminStoreData extends Data {

    use WithApiValidator;

    public function __construct(
        #[Required, Min(3), Max(40)]
        public string $name,

        #[Required, Min(3), Max(40), Unique('admins', 'username')]
        public string $username,

        #[Required, Min(6), Max(40)]
        public string $password,
    ) {

    }
}

<?php

namespace App\Dto\User\Admin;

use App\Dto\GlobalDto\WithApiValidator;
use App\Dto\GlobalDto\WithIndexData;
use Spatie\LaravelData\Data;

class AdminIndexData extends Data {

    use WithApiValidator;
    use WithIndexData;

    public function __construct(
        public ?string $name,
        public ?string $username,
    ) {

    }
}

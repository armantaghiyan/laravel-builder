<?php

namespace App\Services\Domain\User\Admin\Dto;

use App\Services\Infrastructure\Dto\WithApiValidator;
use App\Services\Infrastructure\Dto\WithIndexData;
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

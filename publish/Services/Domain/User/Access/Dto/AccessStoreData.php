<?php

namespace App\Services\Domain\User\Access\Dto;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;
use Symfony\Contracts\Service\Attribute\Required;

class AccessStoreData extends Data {

    #[Required, Max(20)]
    public string $name;
}

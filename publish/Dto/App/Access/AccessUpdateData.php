<?php

namespace App\Dto\App\Access;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;
use Symfony\Contracts\Service\Attribute\Required;

class AccessUpdateData extends Data {

    #[Required, Max(20)]
    public string $name;
}

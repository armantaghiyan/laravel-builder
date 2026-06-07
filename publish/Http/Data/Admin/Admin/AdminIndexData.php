<?php

namespace App\Http\Data\Admin\Admin;

use App\Http\Data\WithApiValidator;
use App\Http\Data\WithIndexData;
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

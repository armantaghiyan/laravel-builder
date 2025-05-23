<?php

namespace App\Services\Domain\User\Access\Dto;

use Spatie\LaravelData\Data;

class AccessPermissionToggleData extends Data {

    public int $permission_id;
    public int $role_id;
}

<?php

namespace App\Services\Domain\User\Access\Dto;

use Spatie\LaravelData\Data;

class AccessRoleToggleData extends Data {

    public int $role_id;
    public int $admin_id;
}

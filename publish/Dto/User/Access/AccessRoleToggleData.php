<?php

namespace App\Dto\User\Access;

use Spatie\LaravelData\Data;

class AccessRoleToggleData extends Data {

    public int $role_id;
    public int $admin_id;
}

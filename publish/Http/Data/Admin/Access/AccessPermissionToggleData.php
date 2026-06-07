<?php

namespace App\Http\Data\Admin\Access;

use Spatie\LaravelData\Data;

class AccessPermissionToggleData extends Data {

    public int $permission_id;
    public int $role_id;
}

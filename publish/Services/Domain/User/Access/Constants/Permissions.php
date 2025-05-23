<?php

namespace App\Services\Domain\User\Access\Constants;

class Permissions {
    const ADMIN_INDEX = 'admin.index';
    const ADMIN_STORE = 'admin.store';
    const ADMIN_UPDATE = 'admin.update';
    const ADMIN_ADD_ROLE = 'admin.add_role';


    const ROLE_INDEX = 'role.index';
    const ROLE_UPDATE = 'role.update';
    const ROLE_STORE = 'role.store';
    const ROLE_DESTROY = 'role.destroy';
}

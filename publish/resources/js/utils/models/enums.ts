export enum Permissions {
    NOTIFICATION_INDEX = 'notification.index',
    NOTIFICATION_STORE = 'notification.store',
    NOTIFICATION_UPDATE = 'notification.update',
    NOTIFICATION_DESTROY = 'notification.destroy',

    LOG_INDEX = 'log.index',
    LOG_UPDATE = 'log.update',

    ADMIN_INDEX = 'admin.index',
    ADMIN_SUPER_ADMIN = 'admin.super_admin',
    ADMIN_STORE = 'admin.store',
    ADMIN_UPDATE = 'admin.update',
    ADMIN_ADD_ROLE = 'admin.add_role',

    ROLE_INDEX = 'role.index',
    ROLE_UPDATE = 'role.update',
    ROLE_STORE = 'role.store',
    ROLE_DESTROY = 'role.destroy',
}

export enum Roles {
    ADMIN = 'admin',
}

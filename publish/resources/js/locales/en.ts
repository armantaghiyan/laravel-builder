export default {
    app_name: 'Panel',
    app: {
        en: 'English',
        fa: 'Persian',
        filter: 'Filter',
        all: 'All',
        edit: 'Edit',
        delete: 'Delete',
        logout: 'Logout',
        profile: 'My Profile',
    },
    global: {
        id: "ID",
        name: "Name",
        status: "Status",
        search: "Search",
        add: "Add",
        customers: "User",
        price: "Amount",
        date: "Date",
        actions: "Actions",
        edit: "Edit",
        update: "Update",
        copy_message: "Text copied successfully",
        copy_message_fail: "Copy failed",
        username: "Username",
        created_at: 'Created At',
        updated_at: 'Updated At',
        submit: 'Submit',
        category_id: 'Category',
        user_id: 'User ID',
        user_guard: 'Guard',
        event: 'Event',
        message: "Message",
        ip_address: "IP Address",
        is_reviewed: "Reviewed",
        target: 'Target',
        target_id: 'Target ID',
        metadata: 'Metadata'
    },
    auth: {
        welcome: 'Welcome to the Panel! 👋',
        login_desc: 'Please sign in to your account and start your adventure',
        username: 'Username',
        enter_username: 'Enter your username',
        password: 'Password',
        repeat_password: 'Repeat Password',
        passwords_do_not_match: 'Passwords do not match',
        sin_in: 'Sign In',
        sin_up: 'Sign Up',
        new_in_platform: 'Do you have an account?',
        create_account: 'Create Account',
        already_have_an_account: 'Already have an account?',
        sign_in_instead: 'Sign In Instead',
        register_desc_1: 'Your adventure starts here 🚀',
        register_desc_2: 'Make managing your application easy and fun!',
    },
    menu: {
        dashboard: 'Dashboard',
        settings: 'Settings',
        list: 'List',
        admin: 'Admins',
        access: 'Roles & Permissions',
        log: 'Logs',
    },
    admin: {
        last_login: 'Last Login',
        admin_detail: 'Admin Details',
        add_a_admin: 'Add New Admin',
        admin_information: 'Admin Information',
        roles: 'Roles',
        change_password: 'Change Password',
        current_password: 'Current Password',
        password_updated: 'Password changed successfully',
    },
    pagination: {
        desc: "Showing {p1} to {p2} of {p3} entries"
    },
    confirm: {
        title: "Are you sure?",
        text: "Are you sure you want to perform this operation?",
        confirm: "Yes",
        cancel: "Cancel"
    },
    roles: {
        admin: 'Admins',
        role: 'Roles',
        "Super Admin": 'Super Admin',
        add_a_role: 'Add New Role',
        role_information: 'Role Information',
        log: 'Logs'
    },
    access: {
        permissions: 'Permissions',
        permission: {
            admin: {
                super_admin: 'Super Admin',
                index: "Admin List",
                store: "Create Admin",
                update: "Update Admin",
                add_role: "Add Role",
            },
            role: {
                index: "Role List",
                store: "Create Role",
                update: "Update Role",
                destroy: "Delete Role",
            },
            log: {
                index: "Log List",
            }
        }
    },
    log: {
        level: "Level",
        name_detail: 'Log Details',
    }
}

import Admin from "@/utils/models/Admin.ts";
import Permission from "@/stores/Permission.ts";
import BaseResponse from "@/utils/api/base.ts";
import Role from "@/utils/models/Role.ts";

export interface AdminLoginResponse extends BaseResponse {

    data: {
        admin: Admin
        api_token: string
    }
}

export interface AdminStartResponse extends BaseResponse {

    data: {
        admin: Admin,
        permissions: Permission[],
        admin_permissions: Permission[],
        enums: AppEnum,
    }
}

export interface AdminIndexResponse extends BaseResponse {

    data: {
        items: Admin[]
        count: number
    }
}

export interface AdminShowResponse extends BaseResponse {

    data: {
        item: Admin
        admin_roles: Role[]
        roles: Role[]
    }
}

export interface AdminStoreAndUpdateResponse extends BaseResponse {

    data: {
        item: Admin
    }
}

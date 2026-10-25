import BaseResponse from "@/utils/api/base.ts";
import Permission from "@/stores/Permission.ts";
import Role from "@/utils/models/Role.ts";
import Admin from "@/utils/models/Admin.ts";

export interface AccessAdminIndexResponse extends BaseResponse {
    data: {
        items: Pick<Admin, 'id' | 'name' | 'username'>[]
        count: number
    }
}

export interface AccessShowResponse extends BaseResponse {

    data: {
        permissions: Permission[]
    }
}

export interface AccessIndexResponse extends BaseResponse {

    data: {
        items: Role[]
    }
}

export interface AccessStoreAndUpdateResponse extends BaseResponse {

    data: {
        item: Role
    }
}

export interface AccessRoleShowResponse extends BaseResponse {

    data: {
        item: Role
    }
}

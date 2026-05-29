import {Permissions} from "@/utils/models/enums.ts";

export const usePermission = () => {

    const $user = userStore();

    function hasPermission(permission: Permissions) {
        // @ts-ignore
        return $user.adminPermissions.find(item => item.name === permission);
    }

    return {hasPermission};
}

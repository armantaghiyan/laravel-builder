import {userStore} from "@/stores/user.ts";
import {Permissions} from "@/utils/models/enums.ts";

export const usePermission = () => {

    const $user = userStore();

    function hasPermission(permission: Permissions) {
        return $user.adminPermissions.find(item => item.name === permission);
    }

    return {hasPermission};
}

import BaseResponse from "@/utils/api/base.ts";
import Role from "@/utils/models/Role.ts";
import Permission from "@/stores/Permission.ts";

export function useAccess() {
    const {callApi, pending} = useCallApi();
    const router = useRouter();
    const alert = useAlert();

    //==================================================================================================================
    function toggleAccess(roleId: number, adminId: number, callback?: () => void) {
        showLoading();
        callApi.post<BaseResponse>('access/role-toggle', {
            role_id: roleId,
            admin_id: adminId,
        }).then(() => {
            callback?.();
        });
    }

    //==================================================================================================================
    function togglePermission(permissionId: number, roleId: number, callback?: () => void) {
        showLoading();
        callApi.post<BaseResponse>('access/permission-toggle', {
            permission_id: permissionId,
            role_id: roleId,
        }).then(() => {
            callback?.();
        });
    }

    //==================================================================================================================
    const rolePermissions = ref<Permission[]>([]);
    const rolePermissionLoaded = ref(false);

    function show(id: string | number) {
        showLoading();
        callApi.get<AccessShowResponse>(`/access/${id}`).then(res => {
            rolePermissions.value = res.data.data.permissions;
            rolePermissionLoaded.value = true;
        });
    }

    //==================================================================================================================

    const items = ref<Role[]>([]);

    function fetchData() {
        showLoading();
        callApi.get<AccessIndexResponse>('/access').then(res => {
            items.value = res.data.data.items
        });
    }

    //==================================================================================================================

    const storeAndUpdateParams = reactive({
        id: '',
        name: '',
    });

    function storeAndUpdate(id: string | number, method: 'post' | 'patch') {
        callApi[method]<AccessStoreAndUpdateResponse>(`/access/${id}`, storeAndUpdateParams).then(res => {
            router.replace({path: `/access/${res.data.data.item.id}`})
        });
    }

    const store = () => storeAndUpdate('', 'post')
    const update = (id: string | number) => storeAndUpdate(id, 'patch')

    //==================================================================================================================

    function showRole(id: string | number, callback?: (role: Role) => void) {
        showLoading();
        callApi.get<AccessRoleShowResponse>(`/access/role/${id}`).then(res => {
            callback?.(res.data.data.item);
        });
    }

    //==================================================================================================================

    async function destroy(id: string | number, callback?: () => void) {
        if (await alert.confirm()) {
            showLoading();
            callApi.delete<BaseResponse>(`/access/${id}`).then(() => {
                callback?.();
            });
        }
    }

    return {
        fetchData,
        items,

        toggleAccess,
        show,
        rolePermissions,
        rolePermissionLoaded,

        togglePermission,

        storeAndUpdateParams,
        store,
        update,
        showRole,

        destroy,
        pending
    }
}

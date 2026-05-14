import {useRouter} from "vue-router";
import {useCallApi} from "@/composables/useCallApi.ts";
import {reactive, ref, watch} from "vue";
import Admin from "@/utils/models/Admin.ts";
import {AdminIndexResponse, AdminShowResponse, AdminStoreAndUpdateResponse} from "@/utils/api/admin.ts";
import Role from "@/utils/models/Role.ts";
import {showLoading} from "@/utils/helper.ts";

export default function useAdmin() {
    const router = useRouter();

    //==================================================================================================================
    const {callApi} = useCallApi();

    const loginForm = reactive({
        username: '',
        password: '',
    });

    //==================================================================================================================

    const items = ref<Admin[]>([]);
    const count = ref(0);
    const params = reactive({
        id: '',
        name: '',
        username: '',
        last_login: '',
        created_at: '',
        updated_at: '',

        search: '',
        page_rows: 7,
        page: 1,
        sort: 'id',
        sort_type: 'desc',
    });

    function fetchData() {
        showLoading();
        callApi.get<AdminIndexResponse>('/admin', {
            params: params,
        }).then(res => {
            items.value = res.data.data.items;
            count.value = res.data.data.count;
        });
    }

    function reFetchData() {
        params.page = 1;
        fetchData();
    }

    watch(() => params.sort, reFetchData);
    watch(() => params.sort_type, reFetchData);
    watch(() => params.page_rows, reFetchData);

    //==================================================================================================================

    const item = ref<Admin>();
    const adminRoles = ref<Role[]>([]);
    const roles = ref<Role[]>([]);

    function show(id: string | number, callback?: (admin: Admin) => void) {
        showLoading();
        callApi.get<AdminShowResponse>(`/admin/${id}`, {
            params: params,
        }).then(res => {
            item.value = res.data.data.item;
            roles.value = res.data.data.roles;
            adminRoles.value = res.data.data.admin_roles;
            callback?.(res.data.data.item);
        });
    }

    //==================================================================================================================
    const storeAndUpdateParams = reactive({
        id: '',
        name: '',
        username: '',
        password: '',
        repeat_password: '',
    });

    function storeAndUpdate(id: string | number, method: 'post'|'patch') {
        showLoading();
        callApi[method]<AdminStoreAndUpdateResponse>(`/admin/${id}`, storeAndUpdateParams).then(res => {
            router.replace({path: `/admin/${res.data.data.item.id}`})
            item.value = res.data.data.item;
        });
    }

    const store = () => storeAndUpdate('', 'post')
    const update = (id: string | number) => storeAndUpdate(id, 'patch')

    return {
        loginForm,

        fetchData,
        items,
        count,
        params,
        reFetchData,

        item,
        adminRoles,
        roles,
        show,

        storeAndUpdateParams,
        store,
        update,
    }
}

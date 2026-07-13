import Admin from "@/utils/models/Admin.ts";
import Role from "@/utils/models/Role.ts";

export default function useAdmin() {
    const router = useRouter();
    const $user = userStore();

    //==================================================================================================================
    const {callApi, pending} = useCallApi();

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

    function storeAndUpdate(id: string | number | null, method: 'post'|'patch') {
        const url = id? `/admin/${id}` : `/admin`;
        callApi[method]<AdminStoreAndUpdateResponse>(url, storeAndUpdateParams).then(res => {
            router.replace({path: `/admin/${res.data.data.item.id}`})
            item.value = res.data.data.item;
        });
    }

    const store = () => storeAndUpdate(null, 'post')
    const update = (id: string | number) => storeAndUpdate(id, 'patch')

    //==================================================================================================================

    function logout() {
        showLoading();
        callApi.post('/admin/logout', {
            params: params,
        }).then(() => {
            $user.logout();
            router.replace({path: '/login'});
        });
    }


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

        logout,
        pending,
    }
}

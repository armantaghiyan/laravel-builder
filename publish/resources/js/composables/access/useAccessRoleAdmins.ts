import {AccessAdminIndexResponse} from "@/utils/api/access.ts";

export default function useAccessRoleAdmins() {
    const {callApi, pending} = useCallApi();
    const items = ref<AccessAdminIndexResponse['data']['items']>([]);
    const count = ref(0);
    const failed = ref(false);
    const params = reactive({page: 1, page_rows: 7});

    function fetchData(roleId: number) {
        failed.value = false;
        items.value = [];

        return callApi.get<AccessAdminIndexResponse>(`/access/role/${roleId}/admins`, {params}).then(res => {
            items.value = res.data.data.items;
            count.value = res.data.data.count;
        }).catch(() => {
            failed.value = true;
        });
    }

    return {fetchData, items, count, params, failed, loading: pending};
}

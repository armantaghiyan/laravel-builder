import AdminSession from "@/utils/models/AdminSession.ts";
import {AdminSessionIndexResponse} from "@/utils/api/adminSession.ts";

export default function useAdminSessionList() {
    const {callApi, pending} = useCallApi();
    const items = ref<AdminSession[]>([]);

    function fetchData(adminId?: string | number) {
        const url = adminId === undefined ? '/admin/profile/sessions' : `/admin/${adminId}/sessions`;

        return callApi.get<AdminSessionIndexResponse>(url).then(res => {
            items.value = res.data.data.items;
        });
    }

    return {fetchData, items, loading: pending};
}

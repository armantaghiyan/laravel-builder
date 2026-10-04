import Notification from "@/utils/models/Notification.ts";
import {NotificationIndexResponse} from "@/utils/api/notification.ts";

export default function useNotificationList() {
    const {callApi, pending} = useCallApi();
    const items = ref<Notification[]>([]);
    const count = ref(0);

    const params = reactive({
        id: '',
        title: '',
        user_type: '',
        user_id: '',
        is_global: '',
        search: '',
        page_rows: 7,
        page: 1,
        sort: 'id',
        sort_type: 'desc',
    });

    function fetchData() {
        return callApi.get<NotificationIndexResponse>('/notification', {params}).then(res => {
            items.value = res.data.data.items;
            count.value = res.data.data.count;
        });
    }

    function reFetchData() {
        params.page = 1;
        return fetchData();
    }

    watch(() => params.sort, reFetchData);
    watch(() => params.sort_type, reFetchData);
    watch(() => params.page_rows, reFetchData);

    return {fetchData, reFetchData, items, count, params, loading: pending};
}

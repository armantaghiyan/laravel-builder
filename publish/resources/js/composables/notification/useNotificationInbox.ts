import {NotificationInboxItem} from "@/utils/models/Notification.ts";
import {NotificationInboxResponse} from "@/utils/api/notification.ts";
import {notificationStore} from "@/stores/notification.ts";

export default function useNotificationInbox() {
    const {callApi, pending} = useCallApi();
    const $notification = notificationStore();
    const items = ref<NotificationInboxItem[]>([]);
    const count = ref(0);
    const params = reactive({
        page: 1,
        page_rows: 7,
        is_read: '' as '' | 0 | 1,
    });

    function fetchData() {
        return callApi.get<NotificationInboxResponse>('/notification/inbox', {params}).then(res => {
            items.value = res.data.data.items;
            count.value = res.data.data.count;
            $notification.unreadCount = res.data.data.unread_count;
        });
    }

    function reFetchData() {
        params.page = 1;
        return fetchData();
    }

    watch(() => params.is_read, reFetchData);
    watch(() => params.page_rows, reFetchData);

    return {fetchData, reFetchData, items, count, params, loading: pending};
}

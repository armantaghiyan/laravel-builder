import Notification from "@/utils/models/Notification.ts";
import {NotificationShowResponse} from "@/utils/api/notification.ts";

export default function useNotificationShow() {
    const {callApi, pending} = useCallApi();
    const item = ref<Notification>();

    function show(id: string | number, callback?: (notification: Notification) => void) {
        showLoading();
        return callApi.get<NotificationShowResponse>(`/notification/${id}`).then(res => {
            item.value = res.data.data.item;
            callback?.(res.data.data.item);
        });
    }

    return {show, item, loading: pending};
}

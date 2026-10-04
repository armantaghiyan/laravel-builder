import {NotificationReadResponse} from "@/utils/api/notification.ts";
import {NotificationInboxItem} from "@/utils/models/Notification.ts";
import {notificationStore} from "@/stores/notification.ts";

export default function useNotificationRead() {
    const {callApi, pending} = useCallApi();
    const $notification = notificationStore();

    function markRead(item: NotificationInboxItem) {
        return callApi.patch<NotificationReadResponse>(`/notification/inbox/${item.id}/read`).then(res => {
            if (!item.is_read) {
                $notification.unreadCount = Math.max(0, $notification.unreadCount - 1);
            }
            Object.assign(item, res.data.data.item);
        });
    }

    return {markRead, pending};
}

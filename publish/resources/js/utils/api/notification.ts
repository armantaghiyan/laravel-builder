import BaseResponse from "@/utils/api/base.ts";
import Notification, {NotificationInboxItem} from "@/utils/models/Notification.ts";

export interface NotificationIndexResponse extends BaseResponse {
    data: {
        items: Notification[],
        count: number,
    }
}

export interface NotificationShowResponse extends BaseResponse {
    data: {
        item: Notification,
    }
}

export interface NotificationStoreAndUpdateResponse extends NotificationShowResponse {}

export interface NotificationInboxResponse extends BaseResponse {
    data: {
        items: NotificationInboxItem[],
        count: number,
        unread_count: number,
    }
}

export interface NotificationReadResponse extends BaseResponse {
    data: {
        item: NotificationInboxItem,
    }
}

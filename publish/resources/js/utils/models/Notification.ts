export default interface Notification {
    id: number,
    title: string,
    message: string,
    url: string | null,
    user_type: 'admin' | 'user' | null,
    user_id: number | null,
    user_name?: string | null,
    is_global: boolean,
    is_read: 0 | 1,
    created_at: string,
    updated_at: string,
}

export interface NotificationInboxItem {
    id: number,
    title: string,
    message: string,
    url: string | null,
    is_global: boolean,
    is_read: 0 | 1,
    created_at: string,
}

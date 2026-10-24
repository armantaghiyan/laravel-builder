export default interface AdminSession {
    id: number;
    is_current: boolean;
    created_at: string;
    last_used_at: string | null;
    expires_at: string | null;
}

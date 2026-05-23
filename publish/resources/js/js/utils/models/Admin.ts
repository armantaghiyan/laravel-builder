export default interface Admin {
    id: number;
    name: string;
    username: string;
    image: string | null;
    last_login: Date;
    created_at: Date;
    updated_at: Date;
}

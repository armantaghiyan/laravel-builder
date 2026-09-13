import Role from "@/utils/models/Role.ts";

export default interface Admin {
    id: number;
    name: string;
    username: string;
    image: string | null;
    roles: Role[];
    last_login: Date;
    created_at: Date;
    updated_at: Date;
}

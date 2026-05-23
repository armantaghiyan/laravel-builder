import {Roles} from "@/utils/models/enums.ts";

export default interface Role {
    id: number,
    name: Roles,
    created_at: Date,
    updated_at: Date,
}

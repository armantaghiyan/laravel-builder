import BaseResponse from "@/utils/api/base.ts";
import AdminSession from "@/utils/models/AdminSession.ts";

export interface AdminSessionIndexResponse extends BaseResponse {
    data: {
        items: AdminSession[];
    }
}

import BaseResponse from "@/utils/api/base.ts";
import Setting from "@/utils/models/Setting.ts";

export interface SettingIndexResponse extends BaseResponse {
    data: {
        items: Setting[];
    };
}

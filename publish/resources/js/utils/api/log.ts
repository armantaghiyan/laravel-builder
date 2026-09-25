import BaseResponse from "@/utils/api/base.ts";
import Log from "@/utils/models/Log.ts";

export interface LogShowResponse extends BaseResponse {

    data: {
        item: Log
    }
}

export interface LogIndexResponse extends BaseResponse {

    data: {
        items: Log[]
        count: number
    }
}

export interface LogRoleShowResponse extends BaseResponse {

    data: {
        item: Log
    }
}

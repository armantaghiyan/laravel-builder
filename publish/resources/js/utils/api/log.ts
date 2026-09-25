import BaseResponse from "@/utils/api/base.ts";
import Log from "@/utils/models/Log.ts";

export interface LogStatistics {
    total: number
    reviewed: number
    unreviewed: number
    reviewed_percentage: number
}

export interface LogShowResponse extends BaseResponse {

    data: {
        item: Log
    }
}

export interface LogIndexResponse extends BaseResponse {

    data: {
        items: Log[]
        count: number
        statistics: LogStatistics
    }
}

export interface LogReportResponse extends BaseResponse {

    data: {
        labels: string[]
        values: number[]
        statistics: LogStatistics
    }
}

export interface LogRoleShowResponse extends BaseResponse {

    data: {
        item: Log
    }
}

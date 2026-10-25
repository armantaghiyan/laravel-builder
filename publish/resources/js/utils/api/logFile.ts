import type BaseResponse from '@/utils/api/base.ts';
import type {LogFileEntry, LogFilePreview} from '@/utils/models/LogFile.ts';

export interface LogFileIndexResponse extends BaseResponse {
    data: {items: LogFileEntry[]}
}

export interface LogFileShowResponse extends BaseResponse {
    data: {item: LogFilePreview}
}

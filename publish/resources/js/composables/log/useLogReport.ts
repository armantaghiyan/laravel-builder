import {ref} from 'vue';
import type {LogReportResponse} from '@/utils/api/log.ts';

export default function useLogReport() {
    const {callApi, pending} = useCallApi();
    const error = ref('');
    const report = ref<LogReportResponse['data'] | null>(null);

    async function fetch(start?: string, end?: string) {
        error.value = '';
        report.value = null;

        try {
            const {data} = await callApi.get<LogReportResponse>('/log/report', {
                params: {start, end},
            });
            report.value = data.data;
        } catch {
            error.value = 'Unable to load the Logger report.';
        }
    }

    return {pending, error, report, fetch};
}

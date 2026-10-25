import type {LogFileShowResponse} from '@/utils/api/logFile.ts';
import type {LogFilePreview} from '@/utils/models/LogFile.ts';

export default function useLogFileShow() {
    const {callApi, pending} = useCallApi();
    const item = ref<LogFilePreview | null>(null);
    const failed = ref(false);

    async function show(path: string, lines: number): Promise<void> {
        item.value = null;
        failed.value = false;

        try {
            const response = await callApi.get<LogFileShowResponse>('/log-files/show', {
                params: {path, lines},
            });
            item.value = response.data.data.item;
        } catch {
            failed.value = true;
        }
    }

    return {item, failed, show, loading: pending};
}

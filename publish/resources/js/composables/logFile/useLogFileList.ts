import type {LogFileIndexResponse} from '@/utils/api/logFile.ts';
import type {LogFileEntry} from '@/utils/models/LogFile.ts';

export default function useLogFileList() {
    const {callApi, pending} = useCallApi();
    const items = ref<LogFileEntry[]>([]);
    const path = ref('');
    const failed = ref(false);

    async function fetchData(directory = path.value): Promise<boolean> {
        failed.value = false;

        try {
            const response = await callApi.get<LogFileIndexResponse>('/log-files', {
                params: {path: directory || undefined},
            });
            items.value = response.data.data.items;
            path.value = directory;
            return true;
        } catch {
            failed.value = true;
            return false;
        }
    }

    return {items, path, failed, fetchData, loading: pending};
}

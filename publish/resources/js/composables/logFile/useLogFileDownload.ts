import type {LogFileEntry, LogFilePreview} from '@/utils/models/LogFile.ts';

export default function useLogFileDownload() {
    const {callApi, pending, progress} = useCallApi();
    const failed = ref(false);
    const downloadingPath = ref('');

    async function download(file: LogFileEntry | LogFilePreview): Promise<void> {
        if (downloadingPath.value) return;
        failed.value = false;
        downloadingPath.value = file.path;

        try {
            const response = await callApi.get<Blob>('/log-files/download', {
                params: {path: file.path},
                responseType: 'blob',
                timeout: 0,
            });
            const url = URL.createObjectURL(response.data);
            const link = document.createElement('a');
            link.href = url;
            link.download = file.name;
            document.body.appendChild(link);
            link.click();
            link.remove();
            window.setTimeout(() => URL.revokeObjectURL(url), 1000);
        } catch {
            failed.value = true;
        } finally {
            downloadingPath.value = '';
        }
    }

    return {download, failed, downloadingPath, progress, loading: pending};
}

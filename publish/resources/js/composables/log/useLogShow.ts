import Log from "@/utils/models/Log.ts";
import {LogShowResponse} from "@/utils/api/log.ts";

export default function useLogShow() {
    const item = ref<Log>();
    const {callApi} = useCallApi();


    function show(id: string | number, callback?: (log: Log) => void) {
        showLoading();
        callApi.get<LogShowResponse>(`/log/${id}`).then(res => {
            item.value = res.data.data.item;
            callback?.(res.data.data.item);
        });
    }

    return {
        item,
        show,
    }
}

import Setting from "@/utils/models/Setting.ts";
import {SettingIndexResponse} from "@/utils/api/setting.ts";

export default function useSettingList() {
    const {callApi, pending} = useCallApi();
    const items = ref<Setting[]>([]);

    function fetchData() {
        return callApi.get<SettingIndexResponse>('/setting').then(res => {
            items.value = res.data.data.items;
        });
    }

    return {fetchData, items, loading: pending};
}

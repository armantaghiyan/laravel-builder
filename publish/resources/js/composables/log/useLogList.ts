import Log from "@/utils/models/Log.ts";
import {LogIndexResponse, LogStatistics} from "@/utils/api/log.ts";


export default function useLogList() {
    const {callApi, pending} = useCallApi();
    const items = ref<Log[]>([]);
    const count = ref(0);
    const statistics = ref<LogStatistics>();

    const params = reactive({
        id: '',
		user_id: '',
		user_guard: '',
		event: '',
		level: '',
		message: '',
		loggable_type: '',
		loggable_id: '',
		ip_address: '',
		is_reviewed: '',
		created_at: '',
		updated_at: '',

        search: '',
        page_rows: 7,
        page: 1,
        sort: 'id',
        sort_type: 'desc',
    });

    function fetchData() {
        callApi.get<LogIndexResponse>('/log', {
            params: params,
        }).then(res => {
            items.value = res.data.data.items;
            count.value = res.data.data.count;
            statistics.value = res.data.data.statistics;
        });
    }

    function reFetchData() {
        params.page = 1;
        fetchData();
    }

    watch(() => params.sort, reFetchData);
    watch(() => params.sort_type, reFetchData);
    watch(() => params.page_rows, reFetchData);

    return {
        fetchData,
        items,
        count,
        statistics,
        params,
        reFetchData,
        loading: pending,
    }
}

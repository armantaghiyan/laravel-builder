import {NotificationStoreAndUpdateResponse} from "@/utils/api/notification.ts";

export default function useNotificationStoreUpdate() {
    const {callApi, pending} = useCallApi();
    const router = useRouter();

    const storeAndUpdateParams = reactive({
        title: '',
        message: '',
        url: '',
        user_type: '' as '' | 'admin' | 'user',
        user_id: '' as string | number,
    });

    function storeAndUpdate(id: string | number | null, method: 'post' | 'patch') {
        const url = id ? `/notification/${id}` : '/notification';
        const payload = {
            ...storeAndUpdateParams,
            url: storeAndUpdateParams.url || null,
            user_type: storeAndUpdateParams.user_type || null,
            user_id: storeAndUpdateParams.user_type ? storeAndUpdateParams.user_id : null,
        };

        return callApi[method]<NotificationStoreAndUpdateResponse>(url, payload).then(res => {
            router.replace({path: `/notification/${res.data.data.item.id}`});
        });
    }

    const store = () => storeAndUpdate(null, 'post');
    const update = (id: string | number) => storeAndUpdate(id, 'patch');

    return {storeAndUpdateParams, store, update, pending};
}

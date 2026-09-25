import {LogShowResponse} from "@/utils/api/log.ts";

export default function useLogUpdateReviewed() {
    const {callApi} = useCallApi();

    function updateReviewed(id: string | number, isReviewed: number) {
        showLoading();

        return callApi.patch<LogShowResponse>(`/log/${id}`, {
            is_reviewed: isReviewed,
        }).then(res => {
            return res.data.data.item;
        });
    }

    return {
        updateReviewed,
    }
}

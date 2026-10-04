import BaseResponse from "@/utils/api/base.ts";

export default function useNotificationDestroy() {
    const {callApi} = useCallApi();
    const alert = useAlert();

    async function destroy(id: string | number, callback?: () => void) {
        if (await alert.confirm()) {
            showLoading();
            return callApi.delete<BaseResponse>(`/notification/${id}`).then(() => {
                callback?.();
            });
        }
    }

    return {destroy};
}

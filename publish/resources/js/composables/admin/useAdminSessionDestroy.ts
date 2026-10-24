import BaseResponse from "@/utils/api/base.ts";

export default function useAdminSessionDestroy() {
    const {callApi} = useCallApi();
    const alert = useAlert();

    async function destroy(id: number, adminId?: string | number, callback?: () => void) {
        if (await alert.confirm()) {
            showLoading();
            const url = adminId === undefined ? `/admin/profile/sessions/${id}` : `/admin/${adminId}/sessions/${id}`;

            return callApi.delete<BaseResponse>(url).then(() => {
                callback?.();
            });
        }
    }

    return {destroy};
}

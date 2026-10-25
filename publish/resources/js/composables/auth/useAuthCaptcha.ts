export function useAuthCaptcha() {
    const {callApi} = useCallApi();
    const captcha = ref<AdminLoginCaptchaResponse['data'] | null>(null);
    const pending = ref(false);
    const failed = ref(false);

    async function load() {
        if (pending.value) return;

        pending.value = true;
        failed.value = false;
        captcha.value = null;

        try {
            const response = await callApi.get<AdminLoginCaptchaResponse>('admin/login/captcha');
            captcha.value = response.data.data;
        } catch {
            failed.value = true;
        } finally {
            pending.value = false;
        }
    }

    return {captcha, pending, failed, load};
}

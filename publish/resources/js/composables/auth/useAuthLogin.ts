export function useAuthLogin() {
    const router = useRouter();
    const {set} = useCookie('api_token', {expires: 7, sameSite: 'strict'});
    const {callApi, pending} = useCallApi();
    const $user = userStore();
    const {captcha, pending: captchaPending, failed: captchaFailed, load: loadCaptcha} = useAuthCaptcha();
    const submitting = ref(false);
    const captchaError = ref('');
    const retryAfter = ref(0);
    let retryTimer: ReturnType<typeof setInterval> | undefined;

    const form = reactive({
        username: '',
        password: '',
        captcha: '',
    });

    async function refreshCaptcha() {
        captchaError.value = '';
        form.captcha = '';
        await loadCaptcha();
    }

    async function login() {
        if (submitting.value || captchaPending.value || retryAfter.value > 0 || !captcha.value) return;

        submitting.value = true;

        try {
            const res = await callApi.post<AdminLoginResponse>('admin/login', {
                ...form,
                captcha_id: captcha.value.captcha_id,
            });
            form.password = '';
            $user.tryGetUser = 0;
            set(res.data.data.api_token);
            $user.login(res.data.data.admin, true);
            await router.replace({path: '/'});
        } catch (error: unknown) {
            // useCallApi displays API errors; keep the server's lockout visible in the form.
            const response = (error as {
                response?: {
                    status?: number,
                    headers?: Record<string, unknown>,
                    data?: {errors?: {captcha?: string[]}},
                },
            })?.response;
            const captchaErrors = response?.data?.errors?.captcha;

            if (response?.status === 429) {
                const seconds = Number(response.headers?.['retry-after']);
                retryAfter.value = Number.isFinite(seconds) && seconds > 0 ? Math.ceil(seconds) : 60;
                const retryAt = Date.now() + retryAfter.value * 1000;
                clearInterval(retryTimer);
                retryTimer = setInterval(() => {
                    retryAfter.value = Math.max(0, Math.ceil((retryAt - Date.now()) / 1000));
                    if (retryAfter.value === 0) {
                        clearInterval(retryTimer);
                        void refreshCaptcha();
                    }
                }, 1000);
            }

            await refreshCaptcha();
            captchaError.value = captchaErrors?.[captchaErrors.length - 1] ?? '';
        } finally {
            submitting.value = false;
        }
    }

    onMounted(refreshCaptcha);
    watch(() => form.captcha, () => { captchaError.value = ''; });
    onUnmounted(() => clearInterval(retryTimer));

    return {
        form,
        login,
        pending: computed(() => pending.value || submitting.value),
        retryAfter,
        captcha,
        captchaPending,
        captchaFailed,
        captchaError,
        refreshCaptcha,
    }
}

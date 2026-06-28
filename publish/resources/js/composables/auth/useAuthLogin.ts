export function useAuthLogin() {
    const router = useRouter();
    const {set} = useCookie('api_token', {expires: 7});
    const {callApi, pending} = useCallApi();
    const $user = userStore();

    const form = reactive({
        username: '',
        password: '',
    });

    function login() {
        callApi.post<AdminLoginResponse>('admin/login', form).then(res => {
            $user.tryGetUser = 0;
            set(res.data.data.api_token);
            $user.login(res.data.data.admin, true);
            router.replace({path: '/'});
        });
    }

    return {
        form,
        login,
        pending,
    }
}

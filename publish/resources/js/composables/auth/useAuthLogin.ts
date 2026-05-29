import {reactive} from "vue";
import {useCallApi} from "@/composables/useCallApi.ts";
import {useRouter} from "vue-router";
import {showLoading} from "@/utils/helper.ts";
import {AdminLoginResponse} from "@/utils/api/admin.ts";
import {userStore} from "@/stores/user.ts";
import {useCookie} from "@/composables/useCookie.ts";

export function useAuthLogin() {
    const router = useRouter();
    const {set} = useCookie('api_token', {expires: 7});
    const {callApi} = useCallApi();
    const $user = userStore();

    const form = reactive({
        username: '',
        password: '',
    });

    function login() {
        showLoading();
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
    }
}

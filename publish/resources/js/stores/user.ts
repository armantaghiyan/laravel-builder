import Admin from "@/utils/models/Admin.ts";
import Permission from "@/stores/Permission.ts";

export const userStore = defineStore('user', {
    state: () => ({
        isAuth: false as boolean,
        user: {} as Partial<Admin>,
        tryGetUser: 0,
        permissions: [] as Permission[],
        adminPermissions: [] as Permission[],
    }),
    actions: {
        login(user: Admin, runAppStart = false) {
            this.user = user;
            this.isAuth = true;

            if (runAppStart) {
                const $user = userStore();
                $user.checkAuth()
            }
        },
        async checkAuth() {
            const {callApi} = useCallApi()
            const $user = userStore();
            const $app = appStore();


            const response = await callApi.get<AdminStartResponse>('admin/start');


            if(response.data.result==='success'){
                if (response.data.data.admin) {
                    $user.login(response.data.data.admin);

                    if (response.data.data.permissions) {
                        $user.setPermissions(response.data.data.permissions);
                    }

                    if (response.data.data.admin_permissions) {
                        $user.setAdminPermissions(response.data.data.admin_permissions);
                    }

                    if (response.data.data.enums) {
                        $app.setEnums(response.data.data.enums);
                    }
                }else{
                    this.logout();
                }
            } else {
                this.logout();
            }
        },
        setPermissions(permissions: Permission[]) {
            this.permissions = permissions;
        },
        setAdminPermissions(adminPermissions: Permission[]) {
            this.adminPermissions = adminPermissions;
        },
        logout() {
            this.user = {};
            this.isAuth = false;
        }
    },
})

import {defineStore} from 'pinia';
import {AppEnum} from "@/utils/models/AppEnum.ts";

export const appStore = defineStore('app', {
    state: () => ({
        loading: true,
        isOpenSidebar: true,
        dir: 'ltr',

        requestLoading: false,

        enums: {} as AppEnum,
    }),
    actions: {
        stopLoading() {
            this.loading = false;
        },
        showLoading() {
            this.requestLoading = true;
        },
        hideLoading() {
            this.requestLoading = false;
        },
        setEnums(enums: AppEnum) {
            this.enums = enums;
        }
    },
})

export const appStore = defineStore('app', {
    state: () => ({
        loading: true,
        isOpenSidebar: true,
        isContentMaxWidth: true,
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
        },
        loadContentMaxWidth() {
            this.isContentMaxWidth = localStorage.getItem('app_content_max_width') !== 'false';
        },
        toggleContentMaxWidth() {
            this.isContentMaxWidth = !this.isContentMaxWidth;
            localStorage.setItem('app_content_max_width', String(this.isContentMaxWidth));
        }
    },
})

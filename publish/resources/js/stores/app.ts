export const appStore = defineStore('app', {
    state: () => ({
        loading: true,
        isOpenSidebar: true,
        isContentMaxWidth: true,
        theme: 'light' as 'light' | 'dark',
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
        },
        loadTheme() {
            const theme = localStorage.getItem('app_theme');
            this.setTheme(theme === 'dark' ? 'dark' : 'light');
        },
        setTheme(theme: 'light' | 'dark') {
            this.theme = theme;
            document.documentElement.classList.toggle('dark', theme === 'dark');
            localStorage.setItem('app_theme', theme);
            window.dispatchEvent(new Event('app-theme-change'));
        },
        toggleTheme() {
            this.setTheme(this.theme === 'light' ? 'dark' : 'light');
        }
    },
})

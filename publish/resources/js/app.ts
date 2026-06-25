import { createApp } from 'vue';
import App from '@/App.vue';
import '~/css/app.css';
import router from "@/router.ts";
import { createPinia } from 'pinia'
import Vue3PersianDatetimePicker from 'vue3-persian-datetime-picker'


const pinia = createPinia();
const app = createApp(App);

app.use(Vue3PersianDatetimePicker, {
    name: 'date-picker',
    props: {
        format: 'YYYY-MM-DD',
        inputFormat: 'YYYY-MM-DD',
        displayFormat: 'jYYYY/jMM/jDD',
        altFormat: 'jYYYY/jMM/jDD',

        editable: false,
        inputClass: 'form-date-control',
        placeholder: 'انتخاب تاریخ',
        color: '#7367f0',
        autoSubmit: true,
    }
})

app.use(pinia);
app.use(router);

app.mount('#app');

import {createI18n} from 'vue-i18n';
import en from '@/locales/en';
import fa from '@/locales/fa';
import {useCookie} from "@/composables/useCookie.ts";


const {value} = useCookie('lang');

const i18n = createI18n({
    legacy: false,
    locale: value.value??'fa',
    fallbackLocale: 'en',
    messages: { en, fa }
});

export default i18n;

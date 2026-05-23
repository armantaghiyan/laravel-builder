// locales/index.ts
import fa from '@/locales/fa';
import en from '@/locales/en';

export const locales = {
    fa,
    en
}

export type LocaleKey = keyof typeof locales
export type TranslationValue = typeof fa

export default locales

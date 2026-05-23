import { ref, computed } from 'vue'
import { locales, type LocaleKey } from '@/locales'

type NestedObject = {
    [key: string]: string | NestedObject
}

const currentLocale = ref<LocaleKey>('fa')

function getNestedValue(obj: NestedObject, path: string): string | undefined {
    return path.split('.').reduce((current: any, key: string) => {
        if (current && typeof current === 'object') {
            return current[key]
        }
        return undefined
    }, obj) as string | undefined
}

export function useTranslations() {
    const t = (key: string, params?: Record<string, string | number>): string => {
        let text = getNestedValue(locales[currentLocale.value] as NestedObject, key)

        if (!text) {
            console.warn(`Translation key "${key}" not found for locale "${currentLocale.value}"`)
            return key
        }

        if (params) {
            Object.entries(params).forEach(([paramKey, paramValue]) => {
                text = text!.replace(new RegExp(`{${paramKey}}`, 'g'), String(paramValue))
            })
        }

        return text
    }

    const setLocale = (locale: LocaleKey): void => {
        if (locales[locale]) {
            currentLocale.value = locale
            localStorage.setItem('app_locale', locale)
            if (locale === 'fa') {
                document.documentElement.dir = 'rtl'
                document.documentElement.lang = 'fa'
            } else {
                document.documentElement.dir = 'ltr'
                document.documentElement.lang = 'en'
            }
        } else {
            console.warn(`Locale "${locale}" not supported`)
        }
    }

    const getLocale = (): LocaleKey => currentLocale.value

    const hasLocale = (locale: string): locale is LocaleKey => {
        return Object.keys(locales).includes(locale)
    }

    const getAvailableLocales = (): LocaleKey[] => Object.keys(locales) as LocaleKey[]

    const getCurrentLocaleInfo = () => {
        const locale = currentLocale.value
        const appTranslations = locales[locale]
        return {
            code: locale,
            name: appTranslations.app?.[locale === 'fa' ? 'fa' : 'en'] || locale,
            direction: locale === 'fa' ? 'rtl' : 'ltr'
        }
    }

    const locale = computed({
        get: () => currentLocale.value,
        set: setLocale
    })

    const loadSavedLocale = (): void => {
        const savedLocale = localStorage.getItem('app_locale') as LocaleKey | null
        if (savedLocale && locales[savedLocale]) {
            currentLocale.value = savedLocale
            if (savedLocale === 'fa') {
                document.documentElement.dir = 'rtl'
                document.documentElement.lang = 'fa'
            } else {
                document.documentElement.dir = 'ltr'
                document.documentElement.lang = 'en'
            }
        } else {
            document.documentElement.dir = 'rtl'
            document.documentElement.lang = 'fa'
        }
    }

    loadSavedLocale()

    return {
        t,
        setLocale,
        getLocale,
        hasLocale,
        getAvailableLocales,
        getCurrentLocaleInfo,
        locale,
        currentLocale
    }
}

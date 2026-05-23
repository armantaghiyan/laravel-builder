// composables/useCookie.ts
import { ref, watch, type Ref } from 'vue'

type SameSite = 'strict' | 'lax' | 'none'

interface CookieOptions {
    expires?: number | Date
    maxAge?: number
    path?: string
    sameSite?: SameSite
}

interface UseCookieReturn<T> {
    value: Ref<T | null>
    set: (value: T, options?: CookieOptions) => void
    remove: () => void
}

function parseCookieValue<T>(value: string | null): T | null {
    if (!value) return null

    try {
        return JSON.parse(value) as T
    } catch {
        return value as unknown as T
    }
}

function serializeCookieValue<T>(value: T): string {
    if (typeof value === 'object' && value !== null) {
        return JSON.stringify(value)
    }
    return String(value)
}

export function useCookie<T = string>(
    key: string,
    options: CookieOptions = {}
): UseCookieReturn<T> {
    const {
        expires,
        maxAge,
        path = '/',
        sameSite = 'lax'
    } = options

    const getCookie = (name: string): string | null => {
        const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'))
        return match ? decodeURIComponent(match[2]) : null
    }

    const cookieValue = ref<T | null>(parseCookieValue<T>(getCookie(key))) as Ref<T | null>

    const setCookie = (value: T, customOptions: CookieOptions = {}): void => {
        const serializedValue = serializeCookieValue(value)
        let cookieString = `${key}=${encodeURIComponent(serializedValue)}`

        // محاسبه زمان انقضا
        const finalExpires = customOptions.expires ?? expires
        if (finalExpires) {
            let expiresDate: Date
            if (typeof finalExpires === 'number') {
                expiresDate = new Date()
                expiresDate.setTime(expiresDate.getTime() + (finalExpires * 24 * 60 * 60 * 1000))
            } else {
                expiresDate = finalExpires
            }
            cookieString += `; expires=${expiresDate.toUTCString()}`
        }

        const finalMaxAge = customOptions.maxAge ?? maxAge
        if (finalMaxAge !== undefined) {
            cookieString += `; max-age=${finalMaxAge}`
        }

        const finalPath = customOptions.path ?? path
        cookieString += `; path=${finalPath}`

        const finalSameSite = customOptions.sameSite ?? sameSite
        cookieString += `; samesite=${finalSameSite}`

        document.cookie = cookieString
        cookieValue.value = value
    }

    const removeCookie = (): void => {
        document.cookie = `${key}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=${path}`
        cookieValue.value = null
    }

    watch(cookieValue, (newValue) => {
        if (newValue === null) {
            removeCookie()
        } else if (newValue !== parseCookieValue<T>(getCookie(key))) {
            setCookie(newValue)
        }
    })

    return {
        value: cookieValue,
        set: setCookie,
        remove: removeCookie
    }
}

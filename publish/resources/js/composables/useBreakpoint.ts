import {onMounted, onUnmounted, ref, type Ref} from 'vue'

type Breakpoint = 'sm' | 'md' | 'lg' | 'xl' | '2xl'

const breakpoints: Record<Breakpoint, number> = {
    sm: 640,
    md: 768,
    lg: 1024,
    xl: 1280,
    '2xl': 1536,
}

export function useBreakpoint(bp: Breakpoint): Ref<boolean> {
    const matches = ref(false)

    const check = () => {
        if (typeof window === 'undefined') return
        matches.value = window.innerWidth >= breakpoints[bp]
    }

    onMounted(() => {
        check()
        window.addEventListener('resize', check)
    })

    onUnmounted(() => {
        window.removeEventListener('resize', check)
    })

    return matches
}

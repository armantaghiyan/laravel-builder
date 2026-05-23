import {ref, onMounted, onBeforeUnmount} from 'vue'

export function useBreakpoint() {
    const breakpoint = ref('');

    const updateBreakpoint = () => {
        const width = window.innerWidth;

        if (width >= 1280) {
            breakpoint.value = 'xl';
        } else if (width >= 1024) {
            breakpoint.value = 'lg';
        } else if (width >= 768) {
            breakpoint.value = 'md';
        } else if (width >= 640) {
            breakpoint.value = 'sm';
        } else {
            breakpoint.value = 'xs';
        }
    }

    onMounted(() => {
        updateBreakpoint();
        window.addEventListener('resize', updateBreakpoint);
    })

    onBeforeUnmount(() => {
        window.removeEventListener('resize', updateBreakpoint);
    })

    return {breakpoint}
}
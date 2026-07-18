<template>
    <transition name="vx-fade">
        <div v-if="visible" class="vx-alert" :class="`vx-alert--${variant}`">
            <div class="vx-alert__icon" v-html="icons?.[variant]"/>

            <div class="vx-alert__body">
                <p v-if="title" class="vx-alert__title">{{ title }}</p>
                <p class="vx-alert__message">
                    <slot>{{ message }}</slot>
                </p>
                <div v-if="$slots.actions" class="vx-alert__actions">
                    <slot name="actions"/>
                </div>
            </div>

            <button
                v-if="dismissible"
                class="vx-alert__close"
                type="button"
                aria-label="بستن"
                @click="dismiss"
            >
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                    <path d="M18 6 6 18"/>
                    <path d="M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </transition>
</template>

<script setup lang="ts">
import {ref, watch} from 'vue'

type Variant = 'primary' | 'success' | 'info' | 'warning' | 'danger' | 'dark'

interface Props {
    variant?: Variant
    title?: string
    message?: string
    dismissible?: boolean
    modelValue?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    variant: 'primary',
    title: '',
    message: '',
    dismissible: false,
    modelValue: true,
})

const emit = defineEmits<{
    (e: 'dismiss'): void
    (e: 'update:modelValue', value: boolean): void
}>()

const visible = ref<boolean>(props.modelValue)

watch(
    () => props.modelValue,
    (value) => {
        visible.value = value
    }
)

function dismiss(): void {
    visible.value = false
    emit('update:modelValue', false)
    emit('dismiss')
}

const icons: Record<Variant, string> = {
    primary: `
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round">
            <path d="M13 2 3 14h7l-1 8 10-12h-7l1-8Z"/>
        </svg>
    `,
    success: `
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 6 9 17l-5-5"/>
        </svg>
    `,
    info: `
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="9"/>
            <path d="M12 16v-5"/>
            <path d="M12 8h.01"/>
        </svg>
    `,
    warning: `
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round">
            <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/>
            <path d="M12 9v4"/>
            <path d="M12 17h.01"/>
        </svg>
    `,
    danger: `
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="9"/>
            <path d="M15 9l-6 6"/>
            <path d="M9 9l6 6"/>
        </svg>
    `,
    dark: `
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="9"/>
            <path d="M12 8v4"/>
            <path d="M12 16h.01"/>
        </svg>
    `,
}
</script>

<style scoped> .vx-alert {
    position: relative;
    display: flex;
    align-items: flex-start;
    gap: 0.85rem;
    padding: 0.95rem 1.1rem;
    border-radius: 0.6rem;
    background: color-mix(in srgb, var(--vx-c-light) 55%, white);
    box-shadow: var(--shadow-xs);
    overflow: hidden;
}

/* thin colored rail on the leading edge (right side in RTL) */
.vx-alert::before {
    content: '';
    position: absolute;
    inset-inline-end: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: var(--vx-c);
    border-radius: 999px;
}

.vx-alert--primary {
    --vx-c: var(--color-primary);
    --vx-c-light: var(--color-light-primary);
}

.vx-alert--success {
    --vx-c: var(--color-success);
    --vx-c-light: var(--color-light-success);
}

.vx-alert--info {
    --vx-c: var(--color-info);
    --vx-c-light: var(--color-light-info);
}

.vx-alert--warning {
    --vx-c: var(--color-warning);
    --vx-c-light: var(--color-light-warning);
}

.vx-alert--danger {
    --vx-c: var(--color-danger);
    --vx-c-light: var(--color-light-danger);
}

.vx-alert--dark {
    --vx-c: var(--color-dark);
    --vx-c-light: var(--color-light-dark);
}

.vx-alert__icon {
    flex: 0 0 auto;
    width: 2.15rem;
    height: 2.15rem;
    border-radius: 0.65rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: color-mix(in srgb, var(--vx-c) 16%, transparent);
    color: var(--vx-c);
}

.vx-alert__icon :deep(svg) {
    width: 1.15rem;
    height: 1.15rem;
}

.vx-alert__body {
    flex: 1 1 auto;
    min-width: 0;
    padding-inline-end: 0.4rem;
}

.vx-alert__title {
    margin: 0 0 0.15rem;
    font-size: 0.925rem;
    font-weight: 700;
    color: color-mix(in srgb, var(--vx-c) 78%, var(--color-base));
}

.vx-alert__message {
    margin: 0;
    font-size: 0.85rem;
    line-height: 1.6;
    color: var(--color-base);
    opacity: 0.85;
}

.vx-alert__actions {
    margin-top: 0.55rem;
    display: flex;
    gap: 0.5rem;
}

.vx-alert__actions :deep(button) {
    border: none;
    cursor: pointer;
    font-size: 0.78rem;
    font-weight: 600;
    padding: 0.35rem 0.75rem;
    border-radius: 0.4rem;
    background: var(--vx-c);
    color: #fff;
}

.vx-alert__close {
    flex: 0 0 auto;
    width: 1.55rem;
    height: 1.55rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    background: transparent;
    color: var(--color-base);
    opacity: 0.4;
    border-radius: 0.4rem;
    cursor: pointer;
    transition: opacity 0.15s ease, background 0.15s ease;
}

.vx-alert__close:hover {
    opacity: 0.85;
    background: rgba(0, 0, 0, 0.05);
}

.vx-alert__close svg {
    width: 0.85rem;
    height: 0.85rem;
}

.vx-fade-enter-active, .vx-fade-leave-active {
    transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1);
    max-height: 200px;
}

.vx-fade-enter-from, .vx-fade-leave-to {
    opacity: 0;
    max-height: 0;
    padding-top: 0;
    padding-bottom: 0;
    transform: scale(0.97);
} </style>

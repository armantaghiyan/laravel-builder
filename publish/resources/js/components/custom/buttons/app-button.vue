<script setup lang="ts">
import { computed, useSlots } from 'vue';

type Variant = 'primary' | 'danger' | 'success' | 'info' | 'warning' | 'dark';
type Size = 'sm' | 'md' | 'lg';

const {
    variant = 'primary',
    size = 'md',
    outline = false,
    disabled = false,
    loading = false,
    block = false,
    rounded = false,
    iconOnly = false,
} = defineProps<{
    variant?: Variant;
    size?: Size;
    outline?: boolean;
    disabled?: boolean;
    loading?: boolean;
    block?: boolean;
    rounded?: boolean;
    iconOnly?: boolean;
}>();

const slots = useSlots();
const hasIconLeft = computed(() => !!slots['icon-left']);
const hasIconRight = computed(() => !!slots['icon-right']);
const hasDefaultSlot = computed(() => !!slots['default']);

/* ── Size classes ───────────────────────────────────────────── */
const sizeClasses = computed(() => {
    if (iconOnly) {
        return {
            sm: 'h-7 w-7 text-sm',
            md: 'h-9 w-9 text-sm',
            lg: 'h-11 w-11 text-base',
        }[size];
    }
    return {
        sm: 'h-7 px-3 text-xs gap-1.5',
        md: 'h-9.5 px-5 text-sm gap-2',
        lg: 'h-11 px-6 text-base gap-2',
    }[size];
});

/* ── Variant color maps ─────────────────────────────────────── */
const solidClasses: Record<Variant, string> = {
    primary: 'bg-primary text-white hover:bg-[#6159d6] active:bg-[#554fc4] shadow-[0_4px_12px_rgba(115,103,240,0.4)] hover:shadow-[0_6px_18px_rgba(115,103,240,0.5)]',
    danger:  'bg-danger  text-white hover:bg-[#e6444a] active:bg-[#cc3c41] shadow-[0_4px_12px_rgba(255,76,81,0.4)]   hover:shadow-[0_6px_18px_rgba(255,76,81,0.5)]',
    success: 'bg-success text-white hover:bg-[#23b363] active:bg-[#1e9d57] shadow-[0_4px_12px_rgba(40,199,111,0.4)]  hover:shadow-[0_6px_18px_rgba(40,199,111,0.5)]',
    info:    'bg-info    text-white hover:bg-[#00a7bc] active:bg-[#0094a7] shadow-[0_4px_12px_rgba(0,186,209,0.4)]   hover:shadow-[0_6px_18px_rgba(0,186,209,0.5)]',
    warning: 'bg-warning text-white hover:bg-[#e68f3c] active:bg-[#cc7f35] shadow-[0_4px_12px_rgba(255,159,67,0.4)]  hover:shadow-[0_6px_18px_rgba(255,159,67,0.5)]',
    dark:    'bg-dark    text-white hover:bg-[#717482] active:bg-[#636671] shadow-[0_4px_12px_rgba(128,131,144,0.4)] hover:shadow-[0_6px_18px_rgba(128,131,144,0.5)]',
};

const outlineClasses: Record<Variant, string> = {
    primary: 'border border-primary text-primary hover:bg-primary hover:text-white',
    danger:  'border border-danger  text-danger  hover:bg-danger  hover:text-white',
    success: 'border border-success text-success hover:bg-success hover:text-white',
    info:    'border border-info    text-info    hover:bg-info    hover:text-white',
    warning: 'border border-warning text-warning hover:bg-warning hover:text-white',
    dark:    'border border-dark    text-dark    hover:bg-dark    hover:text-white',
};

const variantClasses = computed(() =>
    outline ? outlineClasses[variant] : solidClasses[variant]
);
</script>

<template>
    <button
        type="button"
        :disabled="disabled || loading"
        :class="[
            /* Base */
            'relative inline-flex items-center justify-center font-medium select-none',
            'transition-all duration-200 ease-in-out',
            'focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2',
            /* focus ring color per variant */
            {
                'focus-visible:ring-primary': variant === 'primary',
                'focus-visible:ring-danger':  variant === 'danger',
                'focus-visible:ring-success': variant === 'success',
                'focus-visible:ring-info':    variant === 'info',
                'focus-visible:ring-warning': variant === 'warning',
                'focus-visible:ring-dark':    variant === 'dark',
            },
            /* Shape */
            rounded ? 'rounded-full' : 'rounded-md',
            /* Size */
            sizeClasses,
            /* Variant */
            variantClasses,
            /* States */
            'disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none disabled:pointer-events-none',
            'active:scale-[0.97]',
            /* Block */
            block ? 'w-full' : '',
            /* Cursor */
            'cursor-pointer',
        ]"
    >
        <!-- Loading spinner -->
        <span
            v-if="loading"
            class="absolute inset-0 flex items-center justify-center"
        >
            <svg
                class="animate-spin"
                :class="size === 'sm' ? 'h-3.5 w-3.5' : size === 'lg' ? 'h-5 w-5' : 'h-4 w-4'"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
            >
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
        </span>

        <!-- Content wrapper (hidden while loading to preserve width) -->
        <span
            :class="['inline-flex items-center justify-center gap-[inherit]', loading ? 'invisible' : '']"
        >
             <!-- Right icon slot -->
            <span v-if="hasIconRight && !iconOnly" class="shrink-0 flex items-center" :class="size === 'sm' ? 'text-[14px]' : 'text-[16px]'">
                <slot name="icon-right"/>
            </span>

            <!-- Default text slot -->
            <span v-if="hasDefaultSlot && !iconOnly">
                <slot/>
            </span>
            <slot v-else-if="iconOnly"/>

            <!-- Left icon slot -->
            <span v-if="hasIconLeft" class="shrink-0 flex items-center" :class="size === 'sm' ? 'text-[14px]' : 'text-[16px]'">
                <slot name="icon-left"/>
            </span>
        </span>
    </button>
</template>

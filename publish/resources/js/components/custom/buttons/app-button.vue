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
    progress = undefined,
    block = false,
    rounded = false,
    iconOnly = false,
} = defineProps<{
    variant?: Variant;
    size?: Size;
    outline?: boolean;
    disabled?: boolean;
    loading?: boolean;
    progress?: number | null;
    block?: boolean;
    rounded?: boolean;
    iconOnly?: boolean;
}>();

const slots = useSlots();
const hasIconLeft = computed(() => !!slots['icon-left']);
const hasIconRight = computed(() => !!slots['icon-right']);
const hasDefaultSlot = computed(() => !!slots['default']);

/* ── Progress mode ──────────────────────────────────────────── */
const isProgress = computed(
    () => loading && progress !== undefined && progress !== null && !Number.isNaN(progress)
);

const percent = computed(() =>
    Math.min(100, Math.max(0, Math.round(Number(progress ?? 0))))
);

// لایه‌ی پر شده همیشه از چپ به راست به اندازه‌ی درصد نمایش داده می‌شود
const fillStyle = computed(() => ({
    clipPath: `inset(0 ${100 - percent.value}% 0 0)`,
}));

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
    primary: 'bg-primary text-white hover:bg-primary/90 active:bg-primary/80 shadow-[0_4px_12px_theme(colors.primary/40%)] hover:shadow-[0_6px_18px_theme(colors.primary/50%)]',
    danger:  'bg-danger  text-white hover:bg-danger/90  active:bg-danger/80  shadow-[0_4px_12px_theme(colors.danger/40%)]  hover:shadow-[0_6px_18px_theme(colors.danger/50%)]',
    success: 'bg-success text-white hover:bg-success/90 active:bg-success/80 shadow-[0_4px_12px_theme(colors.success/40%)] hover:shadow-[0_6px_18px_theme(colors.success/50%)]',
    info:    'bg-info    text-white hover:bg-info/90    active:bg-info/80    shadow-[0_4px_12px_theme(colors.info/40%)]    hover:shadow-[0_6px_18px_theme(colors.info/50%)]',
    warning: 'bg-warning text-white hover:bg-warning/90 active:bg-warning/80 shadow-[0_4px_12px_theme(colors.warning/40%)] hover:shadow-[0_6px_18px_theme(colors.warning/50%)]',
    dark:    'bg-dark    text-white hover:bg-dark/90    active:bg-dark/80    shadow-[0_4px_12px_theme(colors.dark/40%)]    hover:shadow-[0_6px_18px_theme(colors.dark/50%)]',
};

const outlineClasses: Record<Variant, string> = {
    primary: 'border border-primary text-primary hover:bg-primary hover:text-white',
    danger:  'border border-danger  text-danger  hover:bg-danger  hover:text-white',
    success: 'border border-success text-success hover:bg-success hover:text-white',
    info:    'border border-info    text-info    hover:bg-info    hover:text-white',
    warning: 'border border-warning text-warning hover:bg-warning hover:text-white',
    dark:    'border border-dark    text-dark    hover:bg-dark    hover:text-white',
};

/* پس‌زمینه‌ی نوار (قسمت پر نشده) + رنگ متن روی آن */
const progressTrackClasses: Record<Variant, string> = {
    primary: 'bg-primary/20 text-primary',
    danger:  'bg-danger/20  text-danger',
    success: 'bg-success/20 text-success',
    info:    'bg-info/20    text-info',
    warning: 'bg-warning/20 text-warning',
    dark:    'bg-dark/20    text-dark',
};

/* لایه‌ی پر شده + رنگ متن روی آن */
const progressFillClasses: Record<Variant, string> = {
    primary: 'bg-primary text-white',
    danger:  'bg-danger  text-white',
    success: 'bg-success text-white',
    info:    'bg-info    text-white',
    warning: 'bg-warning text-white',
    dark:    'bg-dark    text-white',
};

const variantClasses = computed(() => {
    if (isProgress.value) return progressTrackClasses[variant];
    return outline ? outlineClasses[variant] : solidClasses[variant];
});
</script>

<template>
    <button
        type="button"
        :disabled="disabled || loading"
        :role="isProgress ? 'progressbar' : undefined"
        :aria-valuemin="isProgress ? 0 : undefined"
        :aria-valuemax="isProgress ? 100 : undefined"
        :aria-valuenow="isProgress ? percent : undefined"
        :aria-busy="loading || undefined"
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
            'disabled:cursor-not-allowed disabled:shadow-none disabled:pointer-events-none',
            isProgress ? 'overflow-hidden' : 'disabled:opacity-50',
            'active:scale-[0.97]',
            /* Block */
            block ? 'w-full' : '',
            /* Cursor */
            'cursor-pointer',
        ]"
    >
        <!-- Progress mode: track text + filled layer -->
        <template v-if="isProgress">
            <!-- متن روی قسمت پر نشده -->
            <span dir="rtl" class="absolute inset-0 flex items-center justify-center tabular-nums">
                {{ percent }}%
            </span>

            <!-- لایه‌ی پر شده با متن سفید (با clip-path برش می‌خورد تا متن در هر دو قسمت خوانا بماند) -->
            <span
                dir="rtl"
                aria-hidden="true"
                class="absolute inset-0 flex items-center justify-center tabular-nums transition-[clip-path] duration-300 ease-out"
                :class="progressFillClasses[variant]"
                :style="fillStyle"
            >
                {{ percent }}%
            </span>
        </template>

        <!-- Loading spinner (بدون progress) -->
        <span
            v-else-if="loading"
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

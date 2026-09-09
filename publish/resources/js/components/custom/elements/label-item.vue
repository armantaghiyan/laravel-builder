<script setup lang="ts">
defineProps<{
    title: string;
    icon?: string;
}>();
</script>

<template>
    <div class="detail-item group flex items-center gap-3 p-3.5 rounded-xl border border-transparent transition-all duration-200 hover:border-primary/10 hover:bg-light-primary/35">
        <span
            class="flex items-center justify-center w-10 h-10 rounded-xl bg-light-primary text-primary shrink-0 transition-transform duration-200 group-hover:scale-105"
        >
            <i :class="['ti', icon || 'ti-circle', 'text-[18px]']"></i>
        </span>

        <div class="flex min-w-0 flex-col gap-0.5 leading-6">
            <span class="font-medium text-[12px] text-gray-500">{{ title }}</span>
            <span class="text-[15px] font-semibold text-gray-800 break-words">
                <slot />
            </span>
        </div>
    </div>
</template>

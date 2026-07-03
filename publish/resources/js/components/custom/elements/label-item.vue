<script setup lang="ts">
defineProps<{
    title: string;
    icon?: string;
}>();
</script>

<template>
    <div class="flex items-center gap-3 py-2">
        <span
            class="flex items-center justify-center w-8 h-8 rounded-full bg-light-primary text-primary shrink-0"
        >
            <i :class="['ti', icon || 'ti-circle', 'text-base']"></i>
        </span>

        <div class="flex flex-wrap items-center gap-1 leading-6">
            <span class="font-medium text-base">{{ title }}:</span>
            <span class="text-gray-700">
                <slot />
            </span>
        </div>
    </div>
</template>

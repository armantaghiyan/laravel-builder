<script setup lang="ts">
defineProps<{
    title?: string
}>();
</script>

<template>
    <div class="bg-surface rounded-2xl border border-border shadow-sm transition-shadow duration-200 hover:shadow-md">
        <div v-if="title" class="px-6 py-5 flex justify-between items-center border-b border-border mb-4">
            <div class="flex items-center gap-3 min-w-0">
                <span class="size-2 rounded-full bg-primary shrink-0 shadow-[0_0_0_5px_rgba(99,91,255,0.10)] animate-pulse"></span>
                <h5 class="text-[17px] font-bold tracking-[-0.2px] truncate">{{title}}</h5>
            </div>
            <slot name="header"/>
        </div>

        <slot/>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    title?: string
}>();
</script>

<template>
    <div class="bg-white rounded-2xl border border-white/80 shadow-[0_10px_30px_rgba(33,43,85,0.07)] transition-shadow duration-300 hover:shadow-[0_14px_35px_rgba(33,43,85,0.11)]">
        <div v-if="title" class="px-6 py-5 flex justify-between items-center border-b border-gray-100 mb-4">
            <div class="flex items-center gap-3 min-w-0">
                <span class="size-2 rounded-full bg-primary shrink-0 shadow-[0_0_0_5px_rgba(99,91,255,0.10)] animate-pulse"></span>
                <h5 class="text-[17px] font-bold tracking-[-0.2px] truncate">{{title}}</h5>
            </div>
            <slot name="header"/>
        </div>

        <slot/>
    </div>
</template>

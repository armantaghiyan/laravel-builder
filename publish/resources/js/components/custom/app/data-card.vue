<script setup lang="ts">
defineProps<{
    value?: string|number
    desc?: string|number
}>();
</script>

<template>
    <card class="p-6 relative overflow-hidden group">
        <div class="absolute -inset-e-7 -top-7 size-24 rounded-full bg-primary/5.5 transition-transform duration-500 group-hover:scale-150"></div>
        <div class="flex items-center gap-3 relative">
            <div class="flex size-11 items-center justify-center rounded-xl bg-linear-to-br from-light-primary to-white text-primary text-[21px] shrink-0 ring-1 ring-primary/10 shadow-sm">
                <slot name="icon" />
            </div>
            <div class="text-[23px] font-bold tracking-tight leading-tight">{{ value }}</div>
        </div>

        <p v-if="desc" class="text-[13px] text-gray-500 leading-snug pt-5 relative">
            {{ desc }}
        </p>
    </card>
</template>

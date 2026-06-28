<script setup lang="ts">
defineProps<{
    value?: string|number
    desc?: string|number
}>();
</script>

<template>
    <card class="p-6">
        <div class="flex items-center gap-2.5">
            <div class="flex size-9.5 items-center justify-center rounded-lg bg-light-dark text-[18px] shrink-0">
                <slot name="icon" />
            </div>
            <div class="text-[22px] font-medium leading-tight">{{ value }}</div>
        </div>

        <p v-if="desc" class="text-[13px] text-gray-4 leading-snug pt-4">
            {{ desc }}
        </p>
    </card>
</template>

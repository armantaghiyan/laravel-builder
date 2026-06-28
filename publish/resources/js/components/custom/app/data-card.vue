<script setup lang="ts">
defineProps<{
    value?: string|number
    desc?: string|number
}>();
</script>

<template>
    <card class="p-6">
        <div class="flex gap-4 items-center">
            <div class="flex items-center justify-center size-10.5 bg-light-dark rounded-md text-[26px]">
                <slot name="icon"/>
            </div>

            <div class="text-[24px] font-medium">{{ value }}</div>
        </div>

        <div v-if="desc" class="ps-14.5 pt-1 text-gray-4">{{ desc }}</div>
    </card>
</template>

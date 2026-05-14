<script setup lang="ts">
defineProps<{
    title: string
}>();
</script>

<template>
    <div class="flex items-center gap-2 pb-4 text-gray-9">
        <i class="ti ti-circle"></i>

        <span>{{title}}:</span>

        <slot/>
    </div>
</template>
<script setup lang="ts">
defineProps<{
    title?: string
}>();
</script>

<template>
    <div class="bg-white rounded-[6px] base-shadow">
        <div v-if="title" class="p-6 flex justify-between items-center">
            <h5 class="text-[18px] font-medium">{{title}}</h5>
            <slot name="header"/>
        </div>

        <slot/>
    </div>
</template>
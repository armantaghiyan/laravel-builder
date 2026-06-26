<script setup lang="ts">
const model = defineModel();

defineProps<{
    tabs: {
        key: string
        label: string
    }[]
}>();
</script>

<template>
    <div class="flex gap-2 border-b border-gray-200">
        <button
            v-for="tab in tabs"
            :key="tab.key"
            class="px-4 py-2 text-sm font-medium transition-colors cursor-pointer"
            :class="model === tab.key? 'border-b-2 border-primary text-primary' : 'text-gray-500 hover:text-gray-700'"
            @click="model = tab.key"
        >
            {{ tab.label }}
        </button>
    </div>
</template>

<style scoped>

</style>

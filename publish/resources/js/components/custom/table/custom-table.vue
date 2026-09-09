<template>
    <div class="resource-table relative overflow-x-auto rounded-2xl border border-gray-200 bg-white shadow-xs">
        <div v-if="loading" class="loading-bar">
            <div class="loading-bar-inner bg-primary"></div>
        </div>
        <table class="min-w-full border-separate border-spacing-0">
            <slot/>
        </table>
    </div>
</template>

<script setup>
import { provide, toRef } from 'vue'

const props = defineProps({
    loading: {
        type: Boolean,
        default: false
    }
})

provide('tableLoading', toRef(props, 'loading'))
</script>

<style scoped>
.loading-bar {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    overflow: hidden;
    z-index: 10;
}

.loading-bar-inner {
    position: absolute;
    top: 0;
    height: 100%;
    width: 40%;
    border-radius: 0 999px 999px 0;
    animation: loading-slide 1.2s ease-in-out infinite;
}

.resource-table::-webkit-scrollbar {
    height: 8px;
}

.resource-table::-webkit-scrollbar-thumb {
    background: #d1d0d4;
    border-radius: 999px;
}

@keyframes loading-slide {
    0% { left: -40%; }
    50% { left: 100%; }
    100% { left: 100%; }
}
</style>

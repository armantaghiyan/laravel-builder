<template>
    <div class="overflow-x-auto relative">
        <div v-if="loading" class="loading-bar">
            <div class="loading-bar-inner bg-primary"></div>
        </div>
        <table class="w-full">
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
    border-radius: 2px;
    animation: loading-slide 1.2s ease-in-out infinite;
}

@keyframes loading-slide {
    0% { left: -40%; }
    50% { left: 100%; }
    100% { left: 100%; }
}
</style>

<script setup lang="ts">
defineProps<{
    duration: number
}>()
</script>

<template>
    <Transition>
        <div>
            <slot/>
        </div>
    </Transition>
</template>

<style>
.v-enter-active,
.v-leave-active {
    transition: opacity v-bind(duration+ 'ms') ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>
<script setup lang="ts">
const {
    disabled = false,
} = defineProps<{
    disabled?: boolean
}>();
</script>

<template>
    <button type="button" :disabled="disabled" class="text-white bg-danger h-9.5 rounded-md px-5 cursor-pointer font-medium disabled:bg-gray-2/60 duration-300">
        <slot/>
    </button>
</template>

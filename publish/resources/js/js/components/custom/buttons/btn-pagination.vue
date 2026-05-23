<script setup lang="ts">
const {
    disabled = false
} = defineProps<{
    isActive: boolean
    disabled?: boolean
}>();
</script>

<template>
    <button
        class="sm:size-9.5 sm:text-[15px] text-[13px] size-8 rounded-[6px] duration-200 flex items-center justify-center cursor-pointer disabled:cursor-default disabled:bg-gray-7 disabled:opacity-50"
        :class="{
            'bg-primary text-white': isActive && !disabled,
            'bg-gray-7 hover:bg-light-primary hover:text-primary': !isActive && !disabled
         }"
        :disabled="disabled"
    >

        <slot/>
    </button>
</template>
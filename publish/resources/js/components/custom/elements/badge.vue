<script setup lang="ts">
const {
    type = 'inline',
    theme = 'primary'
} = defineProps<{
    type?: 'inline' | 'label'
    theme?: 'primary' | 'success' | 'info' | 'warning' | 'danger' | 'dark'
}>();
</script>

<template>
    <div>
        <div v-if="type==='inline'" class="flex items-center gap-1 font-medium" :class="`text-${theme}`">
            <div class="size-3 rounded-full" :class="`bg-${theme}`"></div>
            <div>
                <slot/>
            </div>
        </div>
        <div v-if="type==='label'" class="flex">
            <span v-if="type==='label'" class="flex items-center justify-center text-[13px] font-medium px-2.5 py-1.5 rounded-[4px]"
                 :class="`bg-light-${theme} text-${theme}`">
                <slot/>
            </span>
        </div>
    </div>
</template>
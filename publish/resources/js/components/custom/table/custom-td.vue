<script setup lang="ts">
const {copyToClipboard} = useClipboard();

const {
    copy = null,
} = defineProps<{
    copy?: string | number | null | undefined
}>();

function clickTd() {
    if (copy){
        copyToClipboard(copy);
    }
}
</script>

<template>
    <td class="px-5 py-4 align-middle text-[14px] text-gray-700 transition-colors duration-150" :class="{'cursor-pointer': copy}" @click="clickTd()">
        <slot/>


        <i v-if="copy" class="ti ti-copy px-1 text-gray-400 hover:text-primary"></i>
    </td>
</template>

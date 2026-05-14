<script setup lang="ts">
import {useClipboard} from "@/composables/useClipboard.ts";

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
    <td class="px-5 py-3" :class="{'cursor-pointer': copy}" @click="clickTd()">
        <slot/>


        <i v-if="copy" class="ti ti-copy px-1"></i>
    </td>
</template>

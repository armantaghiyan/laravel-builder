<script setup lang="ts">
import {appStore} from "@/stores/app.ts";
import {useBreakpoint} from "@/composables/useBreakpoint.ts";

defineProps<{
    title: string,
    href: string,
}>();

const {breakpoint} = useBreakpoint();
const $app = appStore();

function clickMenu () {
    if (['xs', 'sm', 'md'].includes(breakpoint.value)){
        $app.isOpenSidebar = false
    }
}
</script>

<template>
    <router-link @click="clickMenu" :to="href" active-class="menu-active-item" class="flex gap-2 w-full items-center h-[38px] px-3 mt-1.5 rounded-[6px] hover:bg-menu-color-light hover:text-white duration-300 cursor-pointer">
        <slot/>
        <span class="text-[15px] pt-1">
            {{title}}
        </span>
    </router-link>
</template>

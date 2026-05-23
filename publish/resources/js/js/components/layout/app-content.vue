<script setup lang="ts">
import {appStore} from "@/stores/app.ts";
import {useBreakpoint} from "@/composables/useBreakpoint.ts";

const $app = appStore()

const {breakpoint} = useBreakpoint();
</script>

<template>
    <div class="relative bg-panel duration-200"
         :class="{
            'w-[calc(100%-260px)] start-[260px]': $app.isOpenSidebar && breakpoint === 'xl',
            'start-0': $app.isOpenSidebar && breakpoint !== 'xl'
        }">

        <div class="max-w-[1390px] mx-auto min-h-screen">
            <slot/>
        </div>
    </div>
</template>

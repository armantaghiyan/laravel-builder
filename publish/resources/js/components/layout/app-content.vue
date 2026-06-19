<script setup lang="ts">
const $app = appStore()

const isXl = useBreakpoint('xl');
</script>

<template>
    <div class="relative bg-panel duration-200"
         :class="{
            'w-[calc(100%-260px)] inset-s-65': $app.isOpenSidebar && isXl,
            'inset-s-0': $app.isOpenSidebar && !isXl
        }">

        <div class="max-w-347.5 mx-auto min-h-screen">
            <slot/>
        </div>
    </div>
</template>

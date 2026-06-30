<script setup lang="ts">
defineProps<{
    title: string;
    href: string;
}>();
</script>

<template>
    <router-link
        :to="href"
        active-class="menu-active-item"
        class="flex gap-2 w-full items-center h-9.5 px-3 mt-1.5 rounded-md hover:bg-menu-color-light hover:text-white duration-300 cursor-pointer"
    >
        <slot />
        <span class="text-[15px] pt-1">
            {{ title }}
        </span>
    </router-link>
</template>

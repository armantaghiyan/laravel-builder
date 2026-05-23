<script setup lang="ts">
import {Menu, MenuButton, MenuItems} from "@headlessui/vue";

const {
    rounded = 6,
    width = 250,
    top = 0,
} = defineProps<{
    width?: number
    rounded?: number
    top?: number
}>();
</script>

<template>
    <Menu as="div" class="relative">
        <div>
            <MenuButton>
                <slot name="button"/>
            </MenuButton>
        </div>

        <transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
        >
            <MenuItems
                class="absolute z-50 bg-white shadow-lg end-0"
                :style="`width: ${width}px;border-radius: ${rounded}px; top: ${top}px`"
            >
                <slot/>
            </MenuItems>
        </transition>
    </Menu>
</template>

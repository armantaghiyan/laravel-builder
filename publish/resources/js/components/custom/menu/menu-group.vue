<script setup lang="ts">
import { ref, watch } from 'vue';
import { useRoute } from 'vue-router';

const props = defineProps<{
    title: string;
    items?: { href: string }[];
}>();

const route = useRoute();

function matchesCurrentRoute() {
    if (!props.items) return false;
    return props.items.some(
        (item) => route.path === item.href || route.path.startsWith(item.href + '/')
    );
}

const isOpen = ref(matchesCurrentRoute());

watch(
    () => route.path,
    () => {
        if (matchesCurrentRoute()) {
            isOpen.value = true;
        }
    }
);
</script>

<template>
    <div class="w-full">
        <button
            type="button"
            class="flex gap-2 w-full items-center h-9.5 px-3 mt-1.5 rounded-md hover:bg-menu-hover hover:text-white duration-300 cursor-pointer"
            @click="isOpen = !isOpen"
        >
            <slot name="icon" />
            <span class="text-[15px] pt-1 flex-1 text-start">
                {{ title }}
            </span>
            <i class="ti ti-chevron-down text-sm duration-300" :class="{ 'rotate-180': isOpen }"></i>
        </button>

        <div
            class="overflow-hidden duration-300 grid"
            :class="isOpen ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'"
        >
            <div class="overflow-hidden">
                <div class="flex flex-col ps-2 border-s border-menu-hover ms-4">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>

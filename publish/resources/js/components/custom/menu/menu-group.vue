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
            class="flex gap-3 w-full items-center h-11 px-3.5 mt-1.5 rounded-xl text-[#aeb5ce] hover:bg-white/8 hover:text-white duration-300 cursor-pointer"
            @click="isOpen = !isOpen"
        >
            <slot name="icon" />
            <span class="text-[14.5px] font-medium pt-1 flex-1 text-start">
                {{ title }}
            </span>
            <i class="ti ti-chevron-down text-sm duration-300" :class="{ 'rotate-180': isOpen }"></i>
        </button>

        <div
            class="overflow-hidden duration-300 grid"
            :class="isOpen ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'"
        >
            <div class="overflow-hidden">
                <div class="flex flex-col ps-2 border-s border-white/10 ms-5">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>

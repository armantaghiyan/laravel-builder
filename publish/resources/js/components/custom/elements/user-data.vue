<script setup lang="ts">
defineProps<{
    name: string,
    image: string,
    sub: string,
}>();
</script>

<template>
    <div class="flex gap-3">
        <div class="flex-none flex items-center">
            <img :src="image" alt="" class="size-8 rounded-full">
        </div>

        <div>
            <div class="text-base font-medium">{{name}}</div>
            <div class="text-gray-6 text-[13px]">{{sub}}</div>
        </div>
    </div>
</template>
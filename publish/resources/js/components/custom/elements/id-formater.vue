<script setup lang="ts">
defineProps<{
    id: string | number
}>();

const parentId = useId();

function shortenId(id: string) {
    if (id.length <= 8) return id;
    return `${id.slice(0, 4)}...${id.slice(-4)}`;
}
</script>

<template>
    <span :id="parentId" class="text-primary">
        <span v-if="typeof id === 'number'">{{id}}</span>
        <tooltip v-else :for="parentId" :text="id">
            <span :id="parentId">{{ shortenId(id) }}</span>
        </tooltip>
    </span>
</template>

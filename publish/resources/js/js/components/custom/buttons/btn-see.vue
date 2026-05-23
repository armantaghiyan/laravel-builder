<script setup lang="ts">
defineProps<{
    href: string
}>();
</script>

<template>
    <router-link :to="href">
        <icon-button>
            <i class="ti ti-eye text-gray-6"></i>
        </icon-button>
    </router-link>
</template>

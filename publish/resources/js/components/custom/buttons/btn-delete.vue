<script setup lang="ts">
const {t} = useTranslations();
</script>

<template>
    <icon-button :aria-label="t('app.delete')" :title="t('app.delete')" class="hover:bg-light-danger! hover:text-danger!">
        <i class="ti ti-trash" aria-hidden="true"></i>
    </icon-button>
</template>

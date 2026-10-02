<script setup lang="ts">
import {useTranslations} from "@/composables/useTranslations.ts";

const {
    disabled = false,
    loading = false,
} = defineProps<{
    disabled?: boolean
    loading?: boolean
}>();

const {t} = useTranslations();
</script>

<template>
    <app-button type="submit" variant="primary" :disabled="disabled" :loading="loading" :aria-label="t('global.search')">
        <template #icon-right>
            <i class="ti ti-search" aria-hidden="true"></i>
        </template>

        <span class="sm:flex hidden">{{ t('global.search') }}</span>
    </app-button>
</template>

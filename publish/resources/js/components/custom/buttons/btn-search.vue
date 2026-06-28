<script setup lang="ts">
import {useTranslations} from "@/composables/useTranslations.ts";

const {
    disabled = false,
} = defineProps<{
    disabled?: boolean
}>();

const {t} = useTranslations();
</script>

<template>
    <app-button type="submit" variant="primary" :disabled="disabled">
        <template #icon-right>
            <span class="pt-2"><i class="ti ti-search"></i></span>
        </template>

        <span class="sm:flex hidden">{{ t('global.search') }}</span>
    </app-button>
</template>

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
    <btn-primary type="submit" :disabled="disabled">
        <span class="sm:flex hidden">{{ t('global.search') }}</span>
        <span class="sm:hidden flex"><i class="ti ti-search"></i></span>
    </btn-primary>
</template>

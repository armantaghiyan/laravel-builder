<script setup lang="ts">
import {useTranslations} from "@/composables/useTranslations.ts";

const {t} = useTranslations();
</script>

<template>
    <btn-primary type="submit">
        <span class="sm:flex hidden">{{ t('global.search') }}</span>
        <span class="sm:hidden flex"><i class="ti ti-search"></i></span>
    </btn-primary>
</template>

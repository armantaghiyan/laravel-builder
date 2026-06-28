<script setup lang="ts">
import {useTranslations} from "@/composables/useTranslations.ts";

const {t} = useTranslations();
</script>

<template>
    <app-button class="flex gap-1 items-center">
        <template #icon-right>
            <i class="ti ti-plus"></i>
        </template>

        <span class="sm:flex hidden">
            {{t('global.add')}}
        </span>
    </app-button>
</template>

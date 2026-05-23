<script setup lang="ts">
import {useTranslations} from "@/composables/useTranslations.ts";

const {t} = useTranslations();
</script>

<template>
    <btn-primary class="flex gap-1 items-center">
        <i class="ti ti-plus text-white"></i>

        <span class="sm:flex hidden">
            {{t('global.add')}}
        </span>
    </btn-primary>
</template>

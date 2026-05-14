<script setup lang="ts">
import {useI18n} from "vue-i18n";

const {t} = useI18n();
</script>

<template>
    <btn-primary class="flex gap-1 items-center">
        <i class="ti ti-plus text-white"></i>

        <span class="sm:flex hidden">
            {{t('global.add')}}
        </span>
    </btn-primary>
</template>

<script setup lang="ts">
import {useI18n} from "vue-i18n";

const {t} = useI18n();
</script>

<template>
    <btn-primary type="submit">
        <span class="sm:flex hidden">{{ t('global.search') }}</span>
        <span class="sm:hidden flex"><i class="ti ti-search"></i></span>
    </btn-primary>
</template>

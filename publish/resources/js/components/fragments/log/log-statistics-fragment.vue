<script setup lang="ts">
import type {LogStatistics} from '@/utils/api/log.ts';

const {t} = useTranslations();

defineProps<{
    statistics: LogStatistics
}>();
</script>

<template>
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <data-card
            :value="statistics.total.toLocaleString()"
            :desc="t('log.total')"
        >
            <template #icon>
                <i class="ti ti-list"></i>
            </template>
        </data-card>

        <data-card
            :value="statistics.reviewed.toLocaleString()"
            :desc="t('log.reviewed')"
        >
            <template #icon>
                <i class="ti ti-check"></i>
            </template>
        </data-card>

        <data-card
            :value="statistics.unreviewed.toLocaleString()"
            :desc="t('log.unreviewed')"
            class="border border-danger/30"
        >
            <template #icon>
                <i class="ti ti-eye-off"></i>
            </template>
        </data-card>

        <data-card
            :value="`${statistics.reviewed_percentage}%`"
            :desc="t('log.reviewed_percentage')"
        >
            <template #icon>
                <i class="ti ti-chart-bar"></i>
            </template>
        </data-card>
    </div>
</template>

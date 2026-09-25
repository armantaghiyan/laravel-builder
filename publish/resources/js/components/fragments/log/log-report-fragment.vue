<script setup lang="ts">
import {computed, onMounted, ref} from 'vue';
import useLogReport from '@/composables/log/useLogReport.ts';
import LogStatisticsFragment from '@/components/fragments/log/log-statistics-fragment.vue';

const {t} = useTranslations();
const {pending: loading, error, report, fetch} = useLogReport();
const dateRange = ref<string[]>([]);
const colors = ['#28C76F', '#FF9F43', '#EA5455', '#7367F0'];
const hasDateRange = computed(() => dateRange.value.length === 2 && dateRange.value.every(Boolean));
const hasPartialDateRange = computed(() => dateRange.value.some(Boolean) && !hasDateRange.value);
const canFetch = computed(() => !hasPartialDateRange.value);

function loadReport() {
    if (!canFetch.value) return;

    fetch(hasDateRange.value ? dateRange.value[0] : undefined, hasDateRange.value ? dateRange.value[1] : undefined);
}

onMounted(loadReport);
</script>

<template>
    <card :title="t('log.report')">
        <form @submit.prevent="loadReport">
            <filter-content>
                <date-input :title="t('global.date')" range v-model="dateRange"/>
            </filter-content>

            <action-content>
                <div class="flex justify-end">
                    <app-button type="submit" variant="primary" :disabled="!canFetch" :loading="loading">
                        <template #icon-right><i class="ti ti-chart-line"/></template>
                        <span>{{ t('log.refresh_report') }}</span>
                    </app-button>
                </div>
            </action-content>
        </form>
    </card>

    <div v-if="loading" class="flex justify-center py-10 text-gray-400">{{ t('log.loading_report') }}</div>
    <div v-else-if="error" class="rounded-lg border border-danger/30 bg-danger/10 px-4 py-3 text-sm text-danger">{{ error }}</div>
    <template v-else-if="report">
        <LogStatisticsFragment :statistics="report.statistics"/>

        <bar-chart
            :title="t('log.by_level')"
            :subtitle="t('log.report_description')"
            :labels="report.labels"
            :datasets="[{label: t('menu.log'), data: report.values, backgroundColor: colors}]"
            :show-legend="false"
            :height="320"
        />

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <card v-for="(label, index) in report.labels" :key="label" class="p-5">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-sm font-medium text-gray-500">{{ label }}</span>
                    <span class="h-3 w-3 rounded-full" :style="{backgroundColor: colors[index % colors.length]}"/>
                </div>
                <div class="mt-3 text-2xl font-bold text-gray-700">{{ report.values[index].toLocaleString() }}</div>
            </card>
        </div>
    </template>
    <div v-else class="rounded-lg border border-dashed border-gray-300 bg-white px-4 py-10 text-center text-gray-400">
        {{ t('log.select_date_range') }}
    </div>
</template>

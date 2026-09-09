<template>
    <card class="line-chart-card p-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <div class="mb-2 h-1 w-10 rounded-full" :style="{ backgroundColor: datasets[0]?.borderColor || colors[0] }"/>
                <h4 class="text-lg font-bold tracking-tight text-gray-700">{{ title }}</h4>
                <p v-if="subtitle" class="mt-1 text-sm leading-6 text-gray-400">{{ subtitle }}</p>
            </div>

            <div v-if="showLegend" class="flex flex-wrap gap-3">
                <span v-for="(dataset, index) in datasets" :key="dataset.label || index" class="legend-item">
                    <span class="h-2.5 w-2.5 rounded-full shadow-sm" :style="{ backgroundColor: dataset.borderColor || colors[index % colors.length] }"/>
                    {{ dataset.label }}
                </span>
            </div>
        </div>

        <div class="chart-wrapper mt-5" :style="{ height: `${height}px` }">
            <canvas ref="chartCanvas"/>
        </div>
    </card>
</template>

<script setup lang="ts">
import {onBeforeUnmount, onMounted, ref, watch} from 'vue';
import {
    CategoryScale,
    Chart,
    Legend,
    LineController,
    LineElement,
    LinearScale,
    PointElement,
    Title,
    Tooltip,
} from 'chart.js';

type Dataset = {
    label: string
    data: number[]
    borderColor?: string
    backgroundColor?: string
}

Chart.register(LineController, LineElement, PointElement, CategoryScale, LinearScale, Tooltip, Legend, Title);

const props = withDefaults(defineProps<{
    labels: string[]
    datasets: Dataset[]
    title?: string
    subtitle?: string
    height?: number
    showLegend?: boolean
}>(), {
    title: 'Price Report',
    subtitle: '',
    height: 320,
    showLegend: true,
});

const colors = ['#7367F0', '#FF9F43', '#28C76F', '#EA5455'];
const chartCanvas = ref<HTMLCanvasElement | null>(null);
let chart: Chart | null = null;

function chartData() {
    return {
        labels: props.labels,
        datasets: props.datasets.map((dataset, index) => {
            const color = dataset.borderColor || colors[index % colors.length];

            return {
                ...dataset,
                borderColor: color,
                backgroundColor: dataset.backgroundColor || `${color}20`,
                borderWidth: 3,
                pointRadius: 2.5,
                pointHoverRadius: 6,
                pointBackgroundColor: '#fff',
                pointBorderWidth: 2,
                tension: 0.35,
                fill: true,
            };
        }),
    };
}

function chartOptions() {
    return {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {mode: 'index' as const, intersect: false},
        plugins: {
            legend: {display: false},
            tooltip: {
                backgroundColor: '#fff',
                titleColor: '#4b4b6b',
                bodyColor: '#6e6b7b',
                borderColor: '#ebe9f1',
                borderWidth: 1,
                padding: {top: 12, right: 14, bottom: 12, left: 14},
                cornerRadius: 10,
                displayColors: false,
                titleFont: {weight: '600'},
                callbacks: {
                    label(context: any) {
                        return ` ${context.dataset.label}: ${Number(context.parsed.y).toLocaleString()}`;
                    },
                },
            },
        },
        scales: {
            x: {
                grid: {display: false},
                border: {display: false},
                ticks: {color: '#b9b7c0', maxRotation: 0, autoSkip: true, maxTicksLimit: 8},
            },
            y: {
                grid: {color: '#ebe9f1', borderDash: [4, 4]},
                border: {display: false},
                grace: '5%',
                ticks: {
                    color: '#b9b7c0',
                    callback(value: string | number) {
                        return Number(value).toLocaleString();
                    },
                },
            },
        },
    };
}

function renderChart() {
    if (!chartCanvas.value) return;

    chart?.destroy();
    chart = new Chart(chartCanvas.value, {
        type: 'line',
        data: chartData(),
        // @ts-ignore
        options: chartOptions(),
    });
}

onMounted(renderChart);
onBeforeUnmount(() => chart?.destroy());
watch(() => [props.labels, props.datasets], renderChart, {deep: true});
</script>

<style scoped>
.chart-wrapper {
    position: relative;
    min-height: 240px;
    width: 100%;
}

.line-chart-card {
    overflow: hidden;
    background: linear-gradient(135deg, #ffffff 0%, #fcfcff 100%);
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border: 1px solid #ebe9f1;
    border-radius: 9999px;
    background: #fff;
    padding: 0.375rem 0.75rem;
    color: #6e6b7b;
    font-size: 0.8125rem;
    font-weight: 600;
}

@media (max-width: 640px) {
    .chart-wrapper { height: 270px !important; }
}
</style>

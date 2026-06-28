<template>
    <card class="p-6">
        <!-- Card Header -->
        <div class="card-header">
            <div class="header-left">
                <h4 class="card-title">{{ title }}</h4>
                <p v-if="subtitle" class="card-subtitle">{{ subtitle }}</p>
            </div>
            <div v-if="showLegend" class="legend">
        <span
            v-for="(dataset, index) in datasets"
            :key="index"
            class="legend-item"
        >
          <span
              class="legend-dot"
              :style="{ backgroundColor: dataset.backgroundColor || defaultColors[index % defaultColors.length] }"
          ></span>
          {{ dataset.label }}
        </span>
            </div>
        </div>

        <!-- Chart Wrapper -->
        <div class="chart-wrapper">
            <canvas ref="chartCanvas"></canvas>
        </div>

        <!-- Footer Stats (optional) -->
        <div v-if="stats && stats.length" class="card-footer">
            <div
                v-for="(stat, index) in stats"
                :key="index"
                class="stat-item"
            >
        <span
            class="stat-indicator"
            :class="stat.trend === 'up' ? 'trend-up' : stat.trend === 'down' ? 'trend-down' : ''"
        ></span>
                <div class="stat-info">
                    <span class="stat-value">{{ stat.value }}</span>
                    <span class="stat-label">{{ stat.label }}</span>
                </div>
            </div>
        </div>
    </card>
</template>

<script>
import { defineComponent, ref, watch, onMounted, onBeforeUnmount } from 'vue'
import {
    Chart,
    BarController,
    BarElement,
    CategoryScale,
    LinearScale,
    Tooltip,
    Legend,
    Title,
} from 'chart.js'

Chart.register(BarController, BarElement, CategoryScale, LinearScale, Tooltip, Legend, Title)

const FONT_FAMILY = "'vazirmatn', Tahoma, sans-serif"

export default defineComponent({
    name: 'BarChart',

    props: {
        /** عنوان کارت */
        title: {
            type: String,
            default: 'نمودار میله‌ای',
        },

        /** زیرعنوان اختیاری */
        subtitle: {
            type: String,
            default: '',
        },

        /**
         * برچسب‌های محور X
         * مثال: ['فروردین', 'اردیبهشت', 'خرداد']
         */
        labels: {
            type: Array,
            required: true,
        },

        /**
         * آرایه‌ای از dataset ها
         * هر آیتم: { label, data[], backgroundColor?, borderRadius?, maxBarThickness? }
         */
        datasets: {
            type: Array,
            required: true,
        },

        /** نمایش legend در هدر */
        showLegend: {
            type: Boolean,
            default: true,
        },

        /** حداکثر مقدار محور Y (اتوماتیک اگر null باشد) */
        yMax: {
            type: Number,
            default: null,
        },

        /** فاصله خطوط محور Y */
        yStepSize: {
            type: Number,
            default: null,
        },

        /** حداکثر عرض میله‌ها (px) */
        maxBarThickness: {
            type: Number,
            default: 42,
        },

        /** گوشه گرد میله‌ها */
        borderRadius: {
            type: Number,
            default: 6,
        },

        /** میله‌های انباشته */
        stacked: {
            type: Boolean,
            default: false,
        },

        /** نمودار افقی */
        horizontal: {
            type: Boolean,
            default: false,
        },

        /**
         * آمار فوتر اختیاری
         * [{ label: 'مجموع', value: '۵۰۴', trend: 'up' | 'down' | null }]
         */
        stats: {
            type: Array,
            default: () => [],
        },

        /** ارتفاع ناحیه چارت (px) */
        height: {
            type: Number,
            default: 280,
        },
    },

    setup(props) {
        const chartCanvas = ref(null)
        let chartInstance = null

        // پالت رنگی روشن Vuexy
        const defaultColors = [
            '#7367F0',
            '#28C76F',
            '#FF9F43',
            '#EA5455',
            '#00CFE8',
            '#FF6480',
        ]

        const buildChartData = () => ({
            labels: props.labels,
            datasets: props.datasets.map((ds, i) => {
                const bg = ds.backgroundColor || defaultColors[i % defaultColors.length]
                return {
                    label: ds.label || `مجموعه ${i + 1}`,
                    data: ds.data,
                    backgroundColor: bg,
                    hoverBackgroundColor: bg + 'cc',
                    borderColor: 'transparent',
                    borderRadius: ds.borderRadius ?? props.borderRadius,
                    borderSkipped: false,
                    maxBarThickness: ds.maxBarThickness ?? props.maxBarThickness,
                }
            }),
        })

        const buildChartOptions = () => ({
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: props.horizontal ? 'y' : 'x',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#ffffff',
                    titleColor: '#4b4b6b',
                    bodyColor: '#6e6b7b',
                    borderColor: '#ebe9f1',
                    borderWidth: 1,
                    padding: { top: 10, bottom: 10, left: 14, right: 14 },
                    cornerRadius: 8,
                    boxShadow: '0 4px 24px rgba(34,41,47,0.1)',
                    titleFont: { size: 12, weight: '600', family: FONT_FAMILY },
                    bodyFont: { size: 12, family: FONT_FAMILY },
                    callbacks: {
                        label(ctx) {
                            return `  ${ctx.dataset.label}: ${ctx.parsed[props.horizontal ? 'x' : 'y']}`
                        }
                    },
                },
            },
            scales: {
                x: {
                    stacked: props.stacked,
                    grid: { display: false, drawBorder: false },
                    border: { display: false },
                    ticks: {
                        color: '#b9b7c0',
                        font: { size: 12, family: FONT_FAMILY },
                        padding: 6,
                    },
                },
                y: {
                    stacked: props.stacked,
                    min: 0,
                    max: props.yMax ?? undefined,
                    grid: {
                        color: '#ebe9f1',
                        drawBorder: false,
                        lineWidth: 1,
                    },
                    border: { display: false, dash: [4, 4] },
                    ticks: {
                        color: '#b9b7c0',
                        font: { size: 12, family: FONT_FAMILY },
                        padding: 10,
                        stepSize: props.yStepSize ?? undefined,
                    },
                },
            },
            animation: {
                duration: 600,
                easing: 'easeInOutQuart',
            },
            layout: { padding: { top: 4, bottom: 0 } },
        })

        const initChart = () => {
            if (!chartCanvas.value) return
            if (chartInstance) {
                chartInstance.destroy()
                chartInstance = null
            }
            chartInstance = new Chart(chartCanvas.value, {
                type: 'bar',
                data: buildChartData(),
                options: buildChartOptions(),
            })
        }

        const updateChart = () => {
            if (!chartInstance) return
            chartInstance.data = buildChartData()
            chartInstance.options = buildChartOptions()
            chartInstance.update('active')
        }

        onMounted(initChart)

        onBeforeUnmount(() => {
            chartInstance?.destroy()
            chartInstance = null
        })

        watch(
            () => [props.labels, props.datasets, props.yMax, props.stacked, props.horizontal],
            updateChart,
            { deep: true }
        )

        return { chartCanvas, defaultColors }
    },
})
</script>

<style scoped>

/* ───── Card Shell ───── */
.bar-chart-card {
    background-color: #ffffff;
    border-radius: 0.875rem;
    padding: 1.5rem;
    box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.1);
    font-family: vazirmatn, 'Vazir',serif;
    width: 100%;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    gap: 1.1rem;
    direction: rtl;
}

/* ───── Header ───── */
.card-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.75rem;
}

.header-left {
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
}

.card-title {
    font-size: 1rem;
    font-weight: 700;
    color: #4b4b6b;
    margin: 0;
    letter-spacing: 0;
}

.card-subtitle {
    font-size: 0.8rem;
    color: #b9b7c0;
    margin: 0;
}

/* ───── Legend ───── */
.legend {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    align-items: center;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    font-size: 0.8rem;
    color: #6e6b7b;
    font-weight: 500;
}

.legend-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}

/* ───── Chart Wrapper ───── */
.chart-wrapper {
    position: relative;
    width: 100%;
    height: v-bind('`${height}px`');
    min-height: 200px;
}

/* ───── Footer Stats ───── */
.card-footer {
    display: flex;
    flex-wrap: wrap;
    gap: 1.25rem;
    padding-top: 0.85rem;
    border-top: 1px solid #ebe9f1;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.625rem;
}

.stat-indicator {
    width: 3px;
    height: 38px;
    border-radius: 4px;
    background-color: #ebe9f1;
    flex-shrink: 0;
}

.stat-indicator.trend-up {
    background-color: #28c76f;
}

.stat-indicator.trend-down {
    background-color: #ea5455;
}

.stat-info {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
}

.stat-value {
    font-size: 0.95rem;
    font-weight: 700;
    color: #4b4b6b;
}

.stat-label {
    font-size: 0.75rem;
    color: #b9b7c0;
}

/* ───── Responsive ───── */
@media (max-width: 640px) {
    .bar-chart-card {
        padding: 1rem;
    }

    .card-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .card-footer {
        gap: 1rem;
    }
}
</style>

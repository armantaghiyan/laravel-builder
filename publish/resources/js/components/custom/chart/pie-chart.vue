<template>
    <card class="pie-chart-card p-6">
        <!-- Card Header -->
        <div class="card-header">
            <div class="header-left">
                <h4 class="card-title">{{ title }}</h4>
                <p v-if="subtitle" class="card-subtitle">{{ subtitle }}</p>
            </div>
            <div v-if="showLegend" class="legend">
        <span
            v-for="(label, index) in labels"
            :key="index"
            class="legend-item"
        >
          <span
              class="legend-dot"
              :style="{ backgroundColor: sliceColor(index) }"
          ></span>
          {{ label }}
        </span>
            </div>
        </div>

        <!-- Chart Wrapper -->
        <div class="chart-wrapper">
            <canvas ref="chartCanvas"></canvas>
            <div v-if="centerLabel" class="center-label">
                <span class="center-value">{{ centerLabel }}</span>
                <span v-if="centerSubLabel" class="center-sub">{{ centerSubLabel }}</span>
            </div>
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
import { defineComponent, ref, computed, watch, onMounted, onBeforeUnmount } from 'vue'
import {
    Chart,
    PieController,
    ArcElement,
    Tooltip,
    Legend,
    Title,
} from 'chart.js'

Chart.register(PieController, ArcElement, Tooltip, Legend, Title)

const FONT_FAMILY = "'vazirmatn', Tahoma, sans-serif"

export default defineComponent({
    name: 'PieChart',

    props: {
        /** عنوان کارت */
        title: {
            type: String,
            default: 'نمودار دایره‌ای',
        },

        /** زیرعنوان اختیاری */
        subtitle: {
            type: String,
            default: '',
        },

        /**
         * برچسب‌های هر بخش دایره
         * مثال: ['فروش', 'بازاریابی', 'پشتیبانی']
         */
        labels: {
            type: Array,
            required: true,
        },

        /**
         * مقادیر عددی متناظر با هر برچسب
         * مثال: [45, 25, 30]
         */
        data: {
            type: Array,
            required: true,
        },

        /**
         * رنگ اختصاصی هر بخش (اختیاری)
         * اگر ارسال نشود از پالت پیش‌فرض استفاده می‌شود
         */
        colors: {
            type: Array,
            default: () => [],
        },

        /** نمایش legend در هدر */
        showLegend: {
            type: Boolean,
            default: true,
        },

        /**
         * میزان توخالی بودن مرکز (۰ = پای کامل، ۶۰-۸۰ = دونات)
         */
        cutout: {
            type: [String, Number],
            default: 0,
        },

        /** فاصله بین بخش‌ها (px) */
        spacing: {
            type: Number,
            default: 2,
        },

        /** گوشه گرد بخش‌ها (px) */
        borderRadius: {
            type: Number,
            default: 4,
        },

        /**
         * متن مرکزی (فقط زمانی معنی‌دار است که cutout > 0 باشد)
         * مثال: '۵۰۴'
         */
        centerLabel: {
            type: String,
            default: '',
        },

        /** زیرمتن مرکزی */
        centerSubLabel: {
            type: String,
            default: '',
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
        numberFormat: {
            type: Boolean,
            default: false,
        },
    },

    setup(props) {
        const chartCanvas = ref(null)
        let chartInstance = null

        // پالت رنگی روشن Vuexy - هماهنگ با BarChart
        const defaultColors = [
            '#7367F0',
            '#28C76F',
            '#FF9F43',
            '#EA5455',
            '#00CFE8',
            '#FF6480',
            '#00B894',
            '#0984E3',
            '#E17055',
            '#D63031',
            '#FD79A8',
            '#A29BFE',
            '#81ECEC',
            '#55EFC4',
            '#FDCB6E',
            '#E84393',
            '#2D3436',
            '#636E72',
            '#74B9FF',
            '#00CEC9',
            '#FAB1A0',
            '#FF7675',
            '#B2BEC3',
            '#8E44AD',
            '#3498DB',
            '#1ABC9C',
            '#2ECC71',
            '#F1C40F',
            '#E67E22',
            '#E74C3C',
            '#34495E',
            '#16A085',
            '#27AE60',
            '#2980B9',
            '#9B59B6',
            '#F39C12',
            '#D35400',
            '#C0392B',
            '#7F8C8D',
            '#5F27CD',
            '#10AC84',
            '#54A0FF',
            '#EE5253',
            '#01A3A4',
            '#FF6B6B',
            '#48DBFB',
            '#1DD1A1',
            '#576574',
            '#8395A7',
        ];

        const sliceColor = (index) =>
            props.colors[index] || defaultColors[index % defaultColors.length]

        const buildChartData = () => ({
            labels: props.labels,
            datasets: [
                {
                    data: props.data,
                    backgroundColor: props.labels.map((_, i) => sliceColor(i)),
                    hoverBackgroundColor: props.labels.map((_, i) => sliceColor(i) + 'cc'),
                    borderColor: '#ffffff',
                    borderWidth: 2,
                    borderRadius: props.borderRadius,
                    spacing: props.spacing,
                    hoverOffset: 6,
                },
            ],
        })

        const buildChartOptions = () => ({
            responsive: true,
            maintainAspectRatio: false,
            cutout: props.cutout,
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
                            const total = ctx.dataset.data.reduce((a, b) => a + b, 0)
                            const pct = total ? Math.round((ctx.parsed / total) * 100) : 0
                            return `  ${ctx.label}: ${props.numberFormat? formatMoney(ctx.parsed, false) : ctx.parsed} (${pct}%)`
                        },
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
                type: 'pie',
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
            () => [props.labels, props.data, props.colors, props.cutout, props.spacing],
            updateChart,
            { deep: true }
        )

        return { chartCanvas, sliceColor }
    },
})
</script>

<style scoped>

/* ───── Card Shell ───── */
.pie-chart-card {
    background-color: #ffffff;
    border-radius: 0.875rem;
    padding: 1.5rem;
    box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.1);
    font-family: vazirmatn, 'Vazir', serif;
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
    display: flex;
    align-items: center;
    justify-content: center;
}

/* ───── Center Label (برای دونات) ───── */
.center-label {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.15rem;
    pointer-events: none;
}

.center-value {
    font-size: 1.3rem;
    font-weight: 700;
    color: #4b4b6b;
}

.center-sub {
    font-size: 0.75rem;
    color: #b9b7c0;
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
    .pie-chart-card {
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

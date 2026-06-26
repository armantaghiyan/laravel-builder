<script setup lang="ts">
</script>
<template>
    <div class="flex flex-col gap-4">
        <!-- نمودار تک رنگ -->
        <bar-chart
            title="فروش ماهانه"
            subtitle="سال ۱۴۰۳"
            :labels="['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور']"
            :datasets="[
                {
                    label: 'فروش',
                    data: [42, 88, 63, 107, 74, 130],
                    backgroundColor: '#7367F0',
                },
            ]"
            :height="280"
            :border-radius="8"
            :stats="[
                { label: 'مجموع فروش', value: '۵۰۴ عدد', trend: 'up' },
                { label: 'بهترین ماه', value: 'شهریور', trend: null },
                { label: 'ضعیف‌ترین ماه', value: 'فروردین', trend: 'down' },
            ]"
        />

        <!-- نمودار چند مجموعه -->
        <bar-chart
            title="مقایسه درآمد و هزینه"
            subtitle="شش ماه اول سال"
            :labels="['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور']"
            :datasets="[
                { label: 'درآمد', data: [90, 120, 80, 150, 110, 170], backgroundColor: '#28C76F' },
                { label: 'هزینه',  data: [60,  75, 55,  95,  70, 100], backgroundColor: '#FF9F43' },
            ]"
            :height="280"
            :max-bar-thickness="32"
        />

        <!-- نمودار افقی -->
        <bar-chart
            title="محبوب‌ترین دسته‌بندی‌ها"
            :labels="['الکترونیک', 'پوشاک', 'خانه و دکور', 'ورزشی', 'غذایی']"
            :datasets="[
                { label: 'سفارش', data: [320, 210, 180, 145, 95], backgroundColor: '#00CFE8' },
            ]"
            :horizontal="true"
            :show-legend="false"
            :height="260"
        />
    </div>
</template>

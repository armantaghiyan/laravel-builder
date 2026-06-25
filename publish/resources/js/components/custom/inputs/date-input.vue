<script setup lang="ts">
const model = defineModel();

const {
    placeholder = 'انتخاب تاریخ',
    format = 'YYYY-MM-DD',
    displayFormat = 'jYYYY/jMM/jDD',
    locale = 'fa',
    autoSubmit = true,
    disabled = false,
    range = false,
    min = '',
    max = '',
    clearable = true,
} = defineProps<{
    title?: string
    placeholder?: string
    format?: string
    displayFormat?: string
    autoSubmit?: boolean
    disabled?: boolean
    locale?: string
    range?: boolean
    min?: string
    max?: string
    clearable?: boolean
}>();

</script>

<template>
    <div ref="parentInput">
        <label v-if="title" class="pb-1 text-[13px]">{{ title }}</label>

        <date-picker
            :placeholder="placeholder"
            :format="format"
            :display-format="displayFormat"
            :locale="locale"
            :autoSubmit="autoSubmit"
            :disabled="disabled"
            :range="range"
            :min="min"
            :max="max"
            :clearable="clearable"
            v-model="model"
        />
    </div>
</template>

<style>
.form-date-control {
    background: white;
    height: 38px;
    border-radius: 6px;
}

.vpd-icon-btn {
    border-radius: 0 6px 6px 0;
}

.vpd-next {
    display: flex;
    align-items: center;
    direction: ltr;
}
</style>

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
.vpd-input-group .form-date-control {
    background: var(--color-surface);
    color: var(--color-foreground);
    border: 1px solid var(--color-gray-300);
    height: 38px;
    border-radius: 6px 0 0 6px;
    outline: none;
    box-shadow: none;
    transition: border-color 150ms;
}

.vpd-input-group:hover .form-date-control {
    border-color: var(--color-gray-600);
}

.vpd-input-group:focus-within .form-date-control {
    border-color: var(--color-primary);
}

.vpd-input-group .form-date-control.error-input {
    border-color: var(--color-danger);
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

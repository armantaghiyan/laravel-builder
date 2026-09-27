<script setup lang="ts">
const props = defineProps<{
    title?: string
    placeholder?: string
    maxlength?: number
    disabled?: boolean
    readonly?: boolean
    clearable?: boolean
    error?: string
    autofocus?: boolean
    rows?: number
    resizable?: boolean
}>();

const emit = defineEmits<{
    focus: [e: FocusEvent]
    blur: [e: FocusEvent]
    enter: [e: KeyboardEvent]
}>();

const model = defineModel<string | number>();
const parentInput = ref<HTMLElement>();
const inputRef = ref<HTMLTextAreaElement>();
const inputId = `textarea-${Math.random().toString(36).slice(2, 9)}`;

watch(model, () => {
    const inputEl = parentInput.value?.querySelector('textarea');
    const errorMessage = parentInput.value?.querySelector('.error-message');
    if (inputEl) inputEl.classList.remove('error-input');
    if (errorMessage) errorMessage.remove();
});

const persianDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
const arabicDigits = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

function toEnglishDigits(value: string): string {
    return value.replace(/[۰-۹٠-٩]/g, (d) => {
        const pIndex = persianDigits.indexOf(d);
        if (pIndex !== -1) return String(pIndex);
        const aIndex = arabicDigits.indexOf(d);
        return aIndex !== -1 ? String(aIndex) : d;
    });
}

const displayValue = computed(() => {
    return model.value === undefined || model.value === null ? '' : String(model.value);
});

function handleInput(e: Event) {
    const input = e.target as HTMLTextAreaElement;
    input.value = toEnglishDigits(input.value);

    if (props.maxlength !== undefined && input.value.length > props.maxlength) {
        input.value = input.value.slice(0, props.maxlength);
    }

    model.value = input.value;
}

function handleKeydown(e: KeyboardEvent) {
    if (e.key === 'Enter' && !e.shiftKey) emit('enter', e);
}

function clear() {
    model.value = '';
    inputRef.value?.focus();
}

defineExpose({ focus: () => inputRef.value?.focus() });
</script>

<template>
    <div ref="parentInput">
        <label v-if="title" :for="inputId" class="pb-1 text-[13px]">{{ title }}</label>

        <div class="relative">
            <span v-if="$slots.prefix" class="absolute top-3 right-3.5 flex items-start">
                <slot name="prefix" />
            </span>

            <textarea
                :id="inputId"
                ref="inputRef"
                class="input auto-placeholder w-full rounded-md border border-gray-300 bg-transparent hover:border-gray-600 focus:border-2 focus:border-primary px-3.5 py-2.5 focus:px-4 duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
                :class="[
                    { 'border-red-500': error },
                    resizable ? 'resize-y' : 'resize-none',
                ]"
                :placeholder="placeholder ?? ''"
                :disabled="disabled"
                :readonly="readonly"
                :maxlength="maxlength"
                :autofocus="autofocus"
                :rows="rows ?? 4"
                :value="displayValue"
                dir="auto"
                @input="handleInput"
                @keydown="handleKeydown"
                @blur="(e) => emit('blur', e)"
                @focus="(e) => emit('focus', e)"
            />

            <button
                v-if="clearable && model"
                type="button"
                tabindex="-1"
                class="absolute top-2.5 left-3.5 text-gray-600 hover:text-gray-500"
                @click="clear"
            >
                ✕
            </button>

            <slot name="suffix" />
        </div>

        <p v-if="error" class="error-message text-red-500 text-xs mt-1">{{ error }}</p>
    </div>
</template>

<style>
.auto-placeholder::placeholder {
    text-align: start;
    direction: auto;
    unicode-bidi: plaintext;
}
</style>

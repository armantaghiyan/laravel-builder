<script setup lang="ts">
const props = defineProps<{
    title?: string
    type?: 'text' | 'password'
    placeholder?: string
    inputmode?: 'none' | 'text' | 'tel' | 'url' | 'email' | 'numeric' | 'decimal' | 'search'
    numberType?: 'int' | 'decimal'
    decimalPlaces?: number
    allowNegative?: boolean
    thousandSeparator?: boolean
    min?: number
    max?: number
    maxlength?: number
    disabled?: boolean
    readonly?: boolean
    clearable?: boolean
    error?: string
    autofocus?: boolean
}>();

const emit = defineEmits<{
    focus: [e: FocusEvent]
    blur: [e: FocusEvent]
    enter: [e: KeyboardEvent]
}>();

const model = defineModel<string | number>();
const parentInput = ref<HTMLElement>();
const inputRef = ref<HTMLInputElement>();
const showPassword = ref(false);
const inputId = `input-${Math.random().toString(36).slice(2, 9)}`;

watch(model, () => {
    const inputEl = parentInput.value?.querySelector('input');
    const errorMessage = parentInput.value?.querySelector('.error-message');
    if (inputEl) inputEl.classList.remove('error-input');
    if (errorMessage) errorMessage.remove();
});

const computedInputmode = computed(() => {
    if (props.inputmode) return props.inputmode;
    if (props.numberType === 'int') return 'numeric';
    if (props.numberType === 'decimal') return 'decimal';
    return 'text';
});

const actualType = computed(() => {
    if (props.type === 'password') return showPassword.value ? 'text' : 'password';
    return 'text';
});

function unformat(value: string): string {
    return value.replace(/,/g, '');
}

function formatThousands(value: string): string {
    if (!value) return value;
    const isNegative = value.startsWith('-');
    const v = isNegative ? value.slice(1) : value;
    const [intPart, decPart] = v.split('.');
    const formattedInt = (intPart || '').replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    let result = formattedInt;
    if (decPart !== undefined) result += '.' + decPart;
    return (isNegative ? '-' : '') + result;
}

function filterNumberValue(raw: string): string {
    let value = raw;

    if (props.numberType === 'int') {
        value = props.allowNegative
            ? value.replace(/(?!^-)[^0-9]/g, '')
            : value.replace(/\D+/g, '');
    } else if (props.numberType === 'decimal') {
        value = props.allowNegative
            ? value.replace(/(?!^-)[^0-9.]/g, '')
            : value.replace(/[^0-9.]/g, '');

        const parts = value.split('.');
        if (parts.length > 2) {
            value = parts[0] + '.' + parts.slice(1).join('');
        }

        if (props.decimalPlaces !== undefined) {
            const [intPart, decPart] = value.split('.');
            if (decPart !== undefined) {
                value = intPart + '.' + decPart.slice(0, props.decimalPlaces);
            }
        }
    }

    if (props.maxlength !== undefined && !props.thousandSeparator) {
        value = value.slice(0, props.maxlength);
    }

    return value;
}

const displayValue = computed(() => {
    const raw = model.value === undefined || model.value === null ? '' : String(model.value);
    if (props.thousandSeparator && props.numberType) {
        return formatThousands(raw);
    }
    return raw;
});

function clampOnBlur() {
    if (!props.numberType || model.value === undefined || model.value === '') return;
    const num = Number(model.value);
    if (Number.isNaN(num)) return;

    let clamped = num;
    if (props.min !== undefined) clamped = Math.max(props.min, clamped);
    if (props.max !== undefined) clamped = Math.min(props.max, clamped);

    if (clamped !== num) model.value = String(clamped);
}

function handleInput(e: Event) {
    const input = e.target as HTMLInputElement;

    if (!props.numberType) {
        if (props.maxlength !== undefined && input.value.length > props.maxlength) {
            input.value = input.value.slice(0, props.maxlength);
        }
        model.value = input.value;
        return;
    }

    const caret = input.selectionStart ?? input.value.length;
    const digitsBeforeCaret = unformat(input.value.slice(0, caret)).length;

    const rawValue = filterNumberValue(unformat(input.value));
    model.value = rawValue;

    const formatted = props.thousandSeparator ? formatThousands(rawValue) : rawValue;
    input.value = formatted;

    let newCaret = formatted.length;
    if (props.thousandSeparator) {
        let digitCount = 0;
        newCaret = formatted.length;
        for (let i = 0; i < formatted.length; i++) {
            if (formatted[i] !== ',') digitCount++;
            if (digitCount === digitsBeforeCaret) {
                newCaret = i + 1;
                break;
            }
        }
        if (digitsBeforeCaret === 0) newCaret = 0;
    } else {
        newCaret = Math.max(0, caret - (unformat(input.value).length - formatted.length));
    }

    requestAnimationFrame(() => input.setSelectionRange(newCaret, newCaret));
}

function handlePaste(e: ClipboardEvent) {
    if (!props.numberType) return;
    e.preventDefault();
    const pasted = e.clipboardData?.getData('text') ?? '';
    const filtered = filterNumberValue(unformat(pasted));
    model.value = filtered;
    const input = e.target as HTMLInputElement;
    input.value = props.thousandSeparator ? formatThousands(filtered) : filtered;
}

function handleKeydown(e: KeyboardEvent) {
    if (e.key === 'Enter') emit('enter', e);
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
            <span v-if="$slots.prefix" class="absolute inset-y-0 right-3.5 flex items-center">
                <slot name="prefix" />
            </span>

            <input
                :id="inputId"
                ref="inputRef"
                class="input auto-placeholder w-full h-9.5 rounded-md border border-gray-300 hover:border-gray-600 focus:border-2 focus:border-primary px-3.5 focus:px-4 duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
                :class="{
                    '!placeholder:text-start': type === 'password',
                    'border-red-500': error,
                }"
                :type="actualType"
                :inputmode="computedInputmode"
                :placeholder="placeholder ?? ''"
                :disabled="disabled"
                :readonly="readonly"
                :maxlength="numberType ? undefined : maxlength"
                :autofocus="autofocus"
                :value="displayValue"
                dir="auto"
                @input="handleInput"
                @paste="handlePaste"
                @keydown="handleKeydown"
                @blur="(e) => { clampOnBlur(); emit('blur', e) }"
                @focus="(e) => emit('focus', e)"
            />

            <button
                v-if="clearable && model"
                type="button"
                tabindex="-1"
                class="absolute inset-y-0 left-3.5 flex items-center text-gray-600 hover:text-gray-500"
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

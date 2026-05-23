<script setup lang="ts">
import {ref, watch} from "vue";

const { type = 'text', inputMode } = defineProps<{
    title?: string
    type?: 'text' | 'password'
    placeholder?: string
    inputMode?: 'int' | 'number'
}>();

const model = defineModel<string | number>();
const parentInput = ref();

watch(model, () => {
    const inputEl = parentInput.value?.querySelector('input');
    const errorMessage = parentInput.value?.querySelector('.error-message');
    if (inputEl) inputEl.classList.remove('error-input');
    if (errorMessage) errorMessage.remove();
});

function handleInput(e: Event) {
    if (!inputMode) return;

    const input = e.target as HTMLInputElement;
    let value = input.value;

    if (inputMode === 'int') {
        value = value.replace(/\D+/g, '');
    }

    else if (inputMode === 'number') {
        value = value.replace(/[^0-9.]/g, '');
        const parts = value.split('.');
        if (parts.length > 2) {
            value = parts[0] + '.' + parts.slice(1).join('');
        }
    }

    input.value = value;
    model.value = value;
}
</script>

<template>
    <div ref="parentInput">
        <label v-if="title" class="pb-1 text-[13px]">{{ title }}</label>
        <input
            class="input auto-placeholder w-full h-[38px] rounded-[6px] border border-gray-2 hover:border-gray-3 focus:border-[2px] focus:border-primary px-3.5 focus:px-4 duration-150"
            :class="{ '!placeholder:text-start': type === 'password' }"
            :type="type"
            :placeholder="placeholder ?? ''"
            dir="auto"
            v-model="model"
            @input="handleInput"
        />
    </div>
</template>

<style>
.auto-placeholder::placeholder {
    text-align: start;
    direction: auto;
    unicode-bidi: plaintext;
}
</style>

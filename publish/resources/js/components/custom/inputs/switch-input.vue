<script setup lang="ts">
const emit = defineEmits<{ (e: 'onClick'): void }>();
const model = defineModel<boolean>({ default: false });

const { disabled = false } = defineProps<{
    href?: string,
    title?: string,
    disabled?: boolean,
}>();

function toggle() {
    if (disabled) return;
    model.value = !model.value;
    emit('onClick');
}
</script>

<template>
    <div class="flex items-center gap-3 py-1">
        <div v-if="!href" class="grow text-gray-700">{{ title }}</div>
        <router-link v-else :to="href" class="grow text-primary">{{ title }}</router-link>

        <button
            type="button"
            role="switch"
            :aria-checked="model"
            :aria-label="title"
            :disabled="disabled"
            :class="[
                model ? 'bg-primary' : 'bg-gray-300',
                disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer',
            ]"
            class="group relative inline-flex h-6 w-12.5 shrink-0 items-center rounded-full
                   shadow-inner transition-colors duration-200 ease-in-out
                   focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/50 focus-visible:ring-offset-2"
            @click="toggle"
        >
            <span
                aria-hidden="true"
                :class="model ? 'inset-s-7' : 'inset-s-0.5'"
                class="pointer-events-none absolute top-0.5 size-5 rounded-full bg-white
                       shadow-md ring-0 transition-all duration-200 ease-out
                       group-active:scale-90"
            />
        </button>
    </div>
</template>

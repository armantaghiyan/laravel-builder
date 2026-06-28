<script setup lang="ts">
const model = defineModel();

defineProps<{
    tabs: {
        key: string
        label: string
        icon?: string  // optional font icon class e.g. 'fa fa-home' or 'i-mdi-home'
    }[]
}>();
</script>

<template>
    <div class="flex border-b border-gray-200">
        <button
            v-for="tab in tabs"
            :key="tab.key"
            class="tab-item group relative flex flex-col items-center px-5 pt-2.5 pb-0 cursor-pointer border-none bg-transparent outline-none rounded-t-lg overflow-hidden"
            :class="{ active: model === tab.key }"
            @click="model = tab.key"
        >
            <!-- hover background -->
            <span class="pointer-events-none absolute inset-0 bg-current opacity-0 group-hover:opacity-[0.06] transition-opacity duration-200 rounded-t-lg" />

            <!-- icon + label row -->
            <span class="relative z-10 flex flex-row-reverse items-center gap-1.5 pb-2.5">
                  <!-- label -->
                <span
                    class="text-sm font-medium whitespace-nowrap transition-colors duration-250"
                    :class="model === tab.key ? 'text-primary' : 'text-gray-500 group-hover:text-gray-700'"
                >
                    {{ tab.label }}
                </span>

                <!-- icon (optional) -->
                <span
                    v-if="tab.icon"
                    :class="[
                        tab.icon,
                        'text-base transition-all duration-250',
                        model === tab.key
                            ? 'text-primary scale-110'
                            : 'text-gray-400 group-hover:text-gray-600'
                    ]"
                />
            </span>

            <!-- active indicator -->
            <span
                v-if="model === tab.key"
                class="tab-indicator absolute bottom-0 left-0 right-0 h-0.5 bg-primary rounded-t-sm"
            />
        </button>
    </div>
</template>

<style scoped>
.tab-indicator {
    animation: slide-in 0.25s cubic-bezier(0.4, 0, 0.2, 1) both;
}

@keyframes slide-in {
    from { transform: scaleX(0); opacity: 0; }
    to   { transform: scaleX(1); opacity: 1; }
}

@media (prefers-reduced-motion: reduce) {
    .tab-indicator { animation: none; }
    .tab-item span { transition: none !important; }
}
</style>

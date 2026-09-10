<script setup lang="ts">
const $app = appStore();
</script>

<template>
    <div>
        <slot/>

        <fade-animate :duration="150">
            <div v-if="$app.requestLoading" class="fixed z-100 top-0 flex items-center justify-center w-full min-h-screen bg-base/40">
                <div class="loader"></div>
            </div>
        </fade-animate>

        <fade-animate :duration="300">
            <div v-if="$app.loading" class="fixed z-100 top-0 flex items-center justify-center w-full min-h-screen bg-white">
                <div class="loader"></div>
            </div>
        </fade-animate>
    </div>
</template>

<style>
.loader {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    border: 2px solid color-mix(in srgb, var(--color-primary) 20%, transparent);
    position: relative;
    animation: loader-radar 1.4s ease-out infinite;
}

.loader::before {
    content: '';
    position: absolute;
    inset: 7px;
    border-radius: 50%;
    background: var(--color-primary);
    opacity: 0.7;
    animation: loader-radar-dot 1.4s ease-in-out infinite;
}

@keyframes loader-radar {
    0% {
        transform: scale(0.75);
        opacity: 0.4;
    }

    70% {
        transform: scale(1);
        opacity: 1;
    }

    100% {
        transform: scale(1.15);
        opacity: 0.2;
    }
}

@keyframes loader-radar-dot {
    0%,
    100% {
        transform: scale(0.7);
    }

    50% {
        transform: scale(1);
    }
}
</style>

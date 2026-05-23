<script setup lang="ts">
import {appStore} from "@/stores/app.ts";

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
    width: 45px;
    aspect-ratio: 1;
    --c: no-repeat linear-gradient(#7367f0 0 0);
    background: var(--c) 0 50%,
    var(--c) 50% 50%,
    var(--c) 100% 50%;
    background-size: 20% 100%;
    animation: l1 1s infinite linear;
}

@keyframes l1 {
    0% {
        background-size: 20% 100%, 20% 100%, 20% 100%
    }
    33% {
        background-size: 20% 10%, 20% 100%, 20% 100%
    }
    50% {
        background-size: 20% 100%, 20% 10%, 20% 100%
    }
    66% {
        background-size: 20% 100%, 20% 100%, 20% 10%
    }
    100% {
        background-size: 20% 100%, 20% 100%, 20% 100%
    }
}
</style>

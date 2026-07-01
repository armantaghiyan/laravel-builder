<script setup lang="ts">
const route = useRoute();

function fullPage() {
    return route.fullPath.includes('/login') || route.fullPath.includes('/register');
}

</script>

<template>
    <app-loading>
        <div v-if="!fullPage()" class="w-full min-h-screen inset-s-0 bg-panel">
            <app-sidebar/>
            <app-content>
                <app-header/>
                <div class="py-6 px-4">
                    <transition name="scale" mode="out-in">
                        <router-view/>
                    </transition>
                </div>
            </app-content>
        </div>

        <div v-else>
            <router-view/>
        </div>
    </app-loading>
</template>

<style scoped>
.scale-enter-active,
.scale-leave-active {
    transition: all 280ms ease;
}

.scale-enter-from {
    opacity: 0;
    transform: scale(0.96);
}

.scale-leave-to {
    opacity: 0;
    transform: scale(1.04);
}
</style>

<script setup lang="ts">
import {useRoute} from "vue-router";

const route = useRoute();

function fullPage() {
    return route.fullPath.includes('/login') || route.fullPath.includes('/register');
}

</script>

<template>
    <i18n-provider>
        <app-loading>
            <div v-if="!fullPage()" class="w-full min-h-screen start-0 bg-panel">
                <app-sidebar/>
                <app-content>
                    <app-header/>
                    <div class="py-6 px-4">
                        <transition name="blur" mode="out-in">
                            <router-view/>
                        </transition>
                    </div>
                </app-content>
            </div>

            <div v-else>
                <router-view/>
            </div>
        </app-loading>
    </i18n-provider>
</template>

<style scoped>
.blur-enter-active,
.blur-leave-active {
    transition: all 300ms ease;
}

.blur-enter-from {
    opacity: 0;
    filter: blur(10px);
}

.blur-leave-to {
    opacity: 0;
    filter: blur(10px);
}
</style>

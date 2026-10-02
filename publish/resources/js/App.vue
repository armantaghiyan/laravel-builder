<script setup lang="ts">
const route = useRoute();
const {t} = useTranslations();

function fullPage() {
    return route.fullPath.includes('/login') || route.fullPath.includes('/register');
}

</script>

<template>
    <app-loading>
        <div v-if="!fullPage()" class="w-full min-h-screen inset-s-0 bg-panel">
            <a href="#main-content" class="fixed start-4 top-4 z-200 -translate-y-24 focus:translate-y-0 rounded-lg bg-surface px-4 py-3 shadow-md">{{ t('ux.skip_to_content') }}</a>
            <app-sidebar/>
            <app-content>
                <app-header/>
                <main id="main-content" tabindex="-1" class="py-6 px-4">
                    <router-view v-slot="{ Component, route }">
                        <keep-alive :max="1">
                            <component
                                :is="Component"
                                :key="route.meta.keepAlive ? route.fullPath : route.name"
                                v-if="route.meta.keepAlive"
                            />
                        </keep-alive>
                        <component
                            :is="Component"
                            :key="!route.meta.keepAlive ? route.fullPath : route.name"
                            v-if="!route.meta.keepAlive"
                        />
                    </router-view>
                </main>
            </app-content>
        </div>

        <div v-else>
            <router-view/>
        </div>
    </app-loading>
</template>

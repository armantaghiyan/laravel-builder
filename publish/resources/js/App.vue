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
                </div>
            </app-content>
        </div>

        <div v-else>
            <router-view/>
        </div>
    </app-loading>
</template>

<script setup lang="ts">
import {Permissions} from "@/utils/models/enums.ts";

const {hasPermission} = usePermission();
const isXl = useBreakpoint('xl');
const $app = appStore()
const {t} = useTranslations();
const route = useRoute();


watch(() => route.path, () => {
    if(!isXl.value) {
        $app.isOpenSidebar = false;
    }
});

watch(isXl, () => {
    $app.isOpenSidebar = !!isXl.value;
});


onMounted(() => {
    $app.isOpenSidebar = !!isXl.value;
})
</script>

<template>
    <div class="relative z-100">
        <fade-animate :duration="200">
            <div v-if="$app.isOpenSidebar && !isXl" @click="$app.isOpenSidebar = false" class="bg-gray-4/50 w-full h-screen fixed to-pink-50 right-0 left-0 z-40"></div>
        </fade-animate>

        <aside class="fixed bg-menu-theme text-menu-color w-65 duration-200 h-full z-40" :class="{'inset-s-0': $app.isOpenSidebar, '-inset-s-65': !$app.isOpenSidebar}">
            <div>
                <div class="h-16 flex items-center gap-2 ps-5.5 pe-2">
                    <logo/>
                    <span class="text-white text-[22px] font-bold">{{ t('app_name')}}</span>
                </div>
                <div class="px-3">
                    <menu-item href="/" :title="t('menu.dashboard')">
                        <i class="menu-icon tf-icons ti ti-smart-home text-[22px]"></i>
                    </menu-item>
                    <menu-item v-if="hasPermission(Permissions.TRANSACTION_INDEX)" href="/transaction" :title="t('menu.transactions')">
                        <i class="ti ti-credit-card-pay text-[22px]"></i>
                    </menu-item>
                    <menu-item v-if="hasPermission(Permissions.CATEGORY_INDEX)" href="/category" :title="t('menu.categories')">
                        <i class="ti ti-category-2 text-[22px]"></i>
                    </menu-item>

                    <menu-group v-if="hasPermission(Permissions.ADMIN_INDEX) || hasPermission(Permissions.ROLE_INDEX)" :title="t('menu.settings')">
                        <template #icon>
                            <i class="ti ti-settings-cog text-[22px]"></i>
                        </template>

                        <menu-item v-if="hasPermission(Permissions.ADMIN_INDEX)" href="/admin" :title="t('menu.admin')">
                            <i class="menu-icon ti ti-user text-[22px]"></i>
                        </menu-item>
                        <menu-item v-if="hasPermission(Permissions.ROLE_INDEX)" href="/access" :title="t('menu.access')">
                            <i class="ti ti-fingerprint text-[22px]"></i>
                        </menu-item>
                    </menu-group>
                </div>
            </div>
        </aside>
    </div>
</template>

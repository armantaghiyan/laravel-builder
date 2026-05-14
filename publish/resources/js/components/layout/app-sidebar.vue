<script setup lang="ts">
import {usePermission} from "@/composables/usePermission.ts";
import {useBreakpoint} from "@/composables/useBreakpoint.ts";
import {appStore} from "@/stores/app.ts";
import {watch} from "vue";
import {Permissions} from "@/utils/models/enums.ts";

const {hasPermission} = usePermission();
const {breakpoint} = useBreakpoint();
const $app = appStore()


watch(breakpoint, () => {
    if(breakpoint.value === 'xl'){
        $app.isOpenSidebar = true;
    }else{
        $app.isOpenSidebar = false;
    }
});
</script>

<template>
    <div class="relative z-100">
        <fade-animate :duration="200">
            <div v-if="$app.isOpenSidebar && breakpoint !== 'xl'" @click="$app.isOpenSidebar = false" class="bg-gray-4/50 w-full h-screen fixed to-pink-50 right-0 left-0 z-40"></div>
        </fade-animate>

        <aside class="fixed bg-menu-theme text-menu-color w-[260px] duration-200 h-full z-40" :class="{'start-0': $app.isOpenSidebar, 'start-[-260px]': !$app.isOpenSidebar}">
            <div>
                <div class="h-16 flex items-center gap-2 ps-[22px] pe-2">
                    <logo/>
                    <span class="text-white text-[22px] font-bold">{{ $t('app_name')}}</span>
                </div>
                <div class="px-3">
                    <menu-item href="/" :title="$t('menu.dashboard')">
                        <i class="menu-icon tf-icons ti ti-smart-home text-[22px]"></i>
                    </menu-item>

                    <menu-section v-if="hasPermission(Permissions.ADMIN_INDEX) || hasPermission(Permissions.ROLE_INDEX)">{{$t('menu.settings')}}</menu-section>
                    <menu-item v-if="hasPermission(Permissions.ADMIN_INDEX)" href="/admin" :title="$t('menu.admin')">
                        <i class="menu-icon ti ti-user text-[22px]"></i>
                    </menu-item>
                    <menu-item v-if="hasPermission(Permissions.ROLE_INDEX)" href="/access" :title="$t('menu.access')">
                        <i class="ti ti-fingerprint text-[22px]"></i>
                    </menu-item>
                </div>
            </div>
        </aside>
    </div>
</template>

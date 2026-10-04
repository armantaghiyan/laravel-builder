<script setup>
import {MenuItem} from '@headlessui/vue';

const $app = appStore()
const $user = userStore()
const {t} = useTranslations();
const {logout} = useAdmin();
const route = useRoute();
const sections = {
    transaction: 'menu.transactions',
    category: 'menu.categories',
    assetBox: 'menu.asset_box',
    admin: 'menu.admin',
    access: 'menu.access',
    assetPrice: 'menu.asset_price',
    log: 'menu.log',
    profile: 'app.profile',
};
const section = computed(() => route.path.split('/')[1]);
const sectionTitle = computed(() => t(sections[section.value] || 'menu.dashboard'));
const isDetail = computed(() => route.path.split('/').filter(Boolean).length > 1);
const pageTitle = computed(() => route.path.includes('/create')
    ? t(route.params.id ? 'global.edit' : 'global.add')
    : t('app.details'));
</script>

<template>
    <div class="sticky top-0 pt-4 px-4 z-50 bg-panel/85 backdrop-blur-xl">
        <card class="min-h-15 flex gap-3 px-3 sm:px-6 items-center justify-between !rounded-xl !shadow-[0_6px_22px_rgba(33,43,85,0.06)]">
            <div class="flex items-center gap-3 min-w-0">
                <icon-button :aria-label="t($app.isOpenSidebar ? 'ux.close_navigation' : 'ux.open_navigation')" :aria-expanded="$app.isOpenSidebar" aria-controls="admin-navigation" @click="$app.isOpenSidebar=!$app.isOpenSidebar">
                    <i class="ti ti-menu-2" aria-hidden="true"></i>
                </icon-button>
                <nav :aria-label="sectionTitle" class="flex items-center gap-2 text-sm min-w-0">
                    <router-link v-if="isDetail" :to="`/${section}`" class="text-gray-600 hover:text-primary truncate">{{ sectionTitle }}</router-link>
                    <span v-else class="font-semibold truncate" aria-current="page">{{ sectionTitle }}</span>
                    <template v-if="isDetail">
                        <i class="ti ti-chevron-right rtl:rotate-180 text-gray-600 shrink-0" aria-hidden="true"></i>
                        <span class="font-semibold whitespace-nowrap" aria-current="page">{{ pageTitle }}</span>
                    </template>
                </nav>
            </div>
            <div class="flex gap-2">
                <div class="flex items-center">
                    <app-header-action/>
                    <notification-bell/>
                </div>

                <option-menu :width="224" :top="60" :label="t('ux.account_menu')">
                    <template #button>
                        <img src="/resources/assets/images/icon/user.jpg" alt="user icon" class="flex-none size-9 rounded-xl mt-1.5 cursor-pointer ring-2 ring-primary/10 transition-transform hover:scale-105">
                    </template>

                    <div class="flex flex-col gap-1">
                        <div class="flex items-center gap-3 border-b border-light-dark p-3">
                            <img src="/resources/assets/images/icon/user.jpg" alt="user icon" class="size-9 rounded-xl mt-1.5 cursor-pointer ring-2 ring-primary/10 transition-transform hover:scale-105">
                            <div class="font-medium text-[15px]">
                                {{$user.user.name}}
                            </div>
                        </div>

                        <div class="p-2">
                            <MenuItem v-slot="{active}">
                                <router-link to="/profile" class="flex items-center gap-2 rounded-lg p-2.5" :class="{'bg-light-primary text-primary': active}">
                                    <i class="ti ti-user-circle text-[22px]" aria-hidden="true"></i>
                                    {{ t(`app.profile`) }}
                                </router-link>
                            </MenuItem>
                        </div>

                        <div class="p-2 pt-0">
                            <MenuItem v-slot="{active}">
                                <button type="button" @click="logout" class="w-full rounded-lg p-2.5 text-start text-danger" :class="{'bg-light-danger': active}">{{ t('app.logout') }}</button>
                            </MenuItem>
                        </div>
                    </div>
                </option-menu>
            </div>
        </card>
    </div>
</template>

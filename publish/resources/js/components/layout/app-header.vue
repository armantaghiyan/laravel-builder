<script setup>
const $app = appStore()
const $user = userStore()
const {t} = useTranslations();
const {logout} = useAdmin();
</script>

<template>
    <div class="sticky top-0 pt-4 px-4 z-50 bg-panel/85 backdrop-blur-xl">
        <card class="h-15 flex px-6 items-center justify-between !rounded-xl !shadow-[0_6px_22px_rgba(33,43,85,0.06)]">
            <div>
                <icon-button @click="$app.isOpenSidebar=!$app.isOpenSidebar">
                    <i class="ti ti-menu-2"></i>
                </icon-button>
            </div>
            <div class="flex gap-2">
                <div class="flex items-center">
                    <app-header-action/>
                    <icon-button>
                        <i class="ti ti-bell ti-md"></i>
                    </icon-button>
                </div>

                <option-menu :width="224" :top="60" position="auto">
                    <template #button>
                        <img src="/resources/assets/images/icon/user.jpg" alt="user icon" class="size-9 rounded-xl mt-1.5 cursor-pointer ring-2 ring-primary/10 transition-transform hover:scale-105">
                    </template>

                    <div class="flex flex-col gap-1">
                        <div class="flex items-center gap-3 border-b border-light-dark p-3">
                            <img src="/resources/assets/images/icon/user.jpg" alt="user icon" class="size-9 rounded-xl mt-1.5 cursor-pointer ring-2 ring-primary/10 transition-transform hover:scale-105">
                            <div class="font-medium text-[15px]">
                                {{$user.user.name}}
                            </div>
                        </div>

                        <div class="p-2">
                            <router-link to="/profile">
                                <btn-clickable class="flex items-center gap-2">
                                    <i class="ti ti-user-circle text-lg text-[22px]"></i>
                                    {{ t(`app.profile`) }}
                                </btn-clickable>
                            </router-link>
                        </div>

                        <div class="p-2 pt-0">
                            <app-button variant="danger" @click="logout" class="w-full">{{ t('app.logout') }}</app-button>
                        </div>
                    </div>
                </option-menu>
            </div>
        </card>
    </div>
</template>

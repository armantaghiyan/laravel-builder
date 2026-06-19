<script setup>
import BtnDanger from "@/components/custom/buttons/btn-danger.vue";

const $app = appStore()
const $user = userStore()
const {t, locale, getAvailableLocales} = useTranslations();
const {logout} = useAdmin();

const changeLanguage = (newLocale) => {
    locale.value = newLocale;
}

</script>

<template>
    <div class="sticky top-0 pt-4 px-4 z-50 bg-panel">
        <card class="h-14 flex px-6 items-center justify-between">
            <div>
                <icon-button @click="$app.isOpenSidebar=!$app.isOpenSidebar">
                    <i class="ti ti-menu-2"></i>
                </icon-button>
            </div>
            <div class="flex gap-2">
                <div class="flex items-center">
                    <option-menu :width="160" :top="60" position="auto">
                        <template #button>
                            <icon-button>
                                <i class="ti ti-language ti-md"></i>
                            </icon-button>
                        </template>

                        <div class="flex flex-col p-2 gap-1">
                            <btn-clickable v-for="lang in getAvailableLocales()" @click="changeLanguage(lang)" :class="{'text-primary bg-light-primary': lang === locale}">
                                {{ t(`app.${lang}`) }}
                            </btn-clickable>
                        </div>
                    </option-menu>
                    <icon-button>
                        <i class="ti ti-bell ti-md"></i>
                    </icon-button>
                </div>

                <option-menu :width="224" :top="60" position="auto">
                    <template #button>
                        <img src="/resources/assets/images/icon/user.jpg" alt="user icon" class="size-10 rounded-full mt-1.5 cursor-pointer">
                    </template>

                    <div class="flex flex-col gap-1">
                        <div class="flex items-center gap-2 border-b border-light-dark p-3">
                            <img src="/resources/assets/images/icon/user.jpg" class="rounded-full size-10" alt="">
                            <div class="font-medium text-[15px]">
                                {{$user.user.name}}
                            </div>
                        </div>

                        <div class="p-2">
                            <btn-danger @click="logout" class="w-full">{{ t('app.logout') }}</btn-danger>
                        </div>
                    </div>
                </option-menu>
            </div>
        </card>
    </div>
</template>

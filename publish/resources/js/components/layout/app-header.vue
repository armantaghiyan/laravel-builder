<script setup>
import {useI18n} from "vue-i18n";
import {appStore} from "@/stores/app.js";

const $app = appStore()
const {locale, availableLocales} = useI18n();

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
                <div class="flex">
                    <option-menu :width="160" :top="60" position="auto">
                        <template #button>
                            <icon-button>
                                <i class="ti ti-language ti-md"></i>
                            </icon-button>
                        </template>

                        <div class="flex flex-col p-2 gap-1">
                            <btn-clickable v-for="lang in availableLocales" @click="changeLanguage(lang)" :class="{'text-primary bg-light-primary': lang === locale}">
                                {{ $t(`app.${lang}`) }}
                            </btn-clickable>
                        </div>
                    </option-menu>
                    <icon-button>
                        <i class="ti ti-bell ti-md"></i>
                    </icon-button>
                </div>

                <img src="/resources/assets/images/icon/user.jpg" alt="user icon" class="size-10 rounded-full">
            </div>
        </card>
    </div>
</template>

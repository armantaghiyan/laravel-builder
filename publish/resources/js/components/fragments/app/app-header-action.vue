<script setup lang="ts">
const $app = appStore()
const {t, locale, getAvailableLocales} = useTranslations();

const changeLanguage = (newLocale: any) => {
    locale.value = newLocale;
}
</script>
<template>
    <icon-button
        :aria-label="$app.theme === 'dark' ? 'Switch to light mode' : 'Switch to dark mode'"
        :title="$app.theme === 'dark' ? 'Switch to light mode' : 'Switch to dark mode'"
        @click="$app.toggleTheme()"
    >
        <i class="ti ti-md" :class="$app.theme === 'dark' ? 'ti-sun' : 'ti-moon-stars'"></i>
    </icon-button>
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
</template>

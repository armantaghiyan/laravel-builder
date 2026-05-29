<script setup lang="ts">
const {locale} = useTranslations();
const {value, set} = useCookie('lang', {expires: 365});

const direction = ref<string>('ltr');

watch(locale, () => {
    setDir();
});

function setDir() {
    set(locale.value)

    if (value.value === 'fa') {
        direction.value = 'rtl';
    } else {
        direction.value = 'ltr';
    }
}


onMounted(() => {
    setDir();
});
</script>

<template>
    <div :style="`direction: ${direction};`">
        <slot/>
    </div>
</template>

<script setup lang="ts">
const {url} = defineProps<{url?: string | null}>();
const {t} = useTranslations();
const router = useRouter();

const safeUrl = computed(() => {
    if (!url || /[\s\\]/u.test(url)) return null;
    if (url.startsWith('/') && !url.startsWith('//')) return url;

    try {
        const parsed = new URL(url);
        if (parsed.protocol === 'http:' || parsed.protocol === 'https:') return parsed.href;
    } catch {}

    return null;
});

const internal = computed(() => {
    if (!safeUrl.value) return null;
    const parsed = new URL(safeUrl.value, window.location.origin);
    const path = `${parsed.pathname}${parsed.search}${parsed.hash}`;
    return parsed.origin === window.location.origin && router.resolve(path).matched.length ? path : null;
});

const external = computed(() => safeUrl.value
    && new URL(safeUrl.value, window.location.origin).origin !== window.location.origin);
</script>

<template>
    <router-link v-if="internal" :to="internal" class="text-primary hover:underline break-all">
        <slot>{{ t('notification.open_link') }}</slot>
    </router-link>
    <a v-else-if="safeUrl" :href="safeUrl" :target="external ? '_blank' : undefined" rel="noopener noreferrer" class="text-primary hover:underline break-all">
        <slot>{{ t('notification.open_link') }}</slot>
        <i v-if="external" class="ti ti-external-link ms-1" aria-hidden="true"></i>
    </a>
</template>

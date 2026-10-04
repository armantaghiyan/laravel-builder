<script setup lang="ts">
import useNotificationShow from "@/composables/notification/useNotificationShow.ts";
import useNotificationStoreUpdate from "@/composables/notification/useNotificationStoreUpdate.ts";

const {t} = useTranslations();
const route = useRoute();
const {show, loading} = useNotificationShow();
const {storeAndUpdateParams, store, update, pending} = useNotificationStoreUpdate();
const updateMode = computed(() => !!route.params.id);
const recipientTypes = computed(() => [
    {value: '', label: t('notification.global')},
    {value: 'admin', label: t('notification.admin')},
    {value: 'user', label: t('notification.user')},
]);

function submitForm() {
    if (updateMode.value) {
        update(route.params.id as string);
    } else {
        store();
    }
}

watch(() => storeAndUpdateParams.user_type, () => {
    storeAndUpdateParams.user_id = '';
});

onMounted(() => {
    if (updateMode.value) {
        show(route.params.id as string, async item => {
            storeAndUpdateParams.title = item.title;
            storeAndUpdateParams.message = item.message;
            storeAndUpdateParams.url = item.url || '';
            storeAndUpdateParams.user_type = item.user_type || '';
            await nextTick();
            storeAndUpdateParams.user_id = item.user_id || '';
        });
    }
});
</script>

<template>
    <div>
        <div class="pb-6 pt-2"><h4 class="font-medium text-[24px] pb-2">{{ t(updateMode ? 'notification.edit' : 'notification.send') }}</h4></div>
        <card :title="t('notification.information')">
            <form @submit.prevent="submitForm" class="px-6 pb-6 flex flex-col gap-6">
                <text-input id="title" :title="t('notification.title')" :maxlength="160" :disabled="loading" v-model="storeAndUpdateParams.title"/>
                <textarea-input id="message" :title="t('global.message')" :maxlength="10000" :disabled="loading" v-model="storeAndUpdateParams.message"/>
                <select-input id="user_type" :title="t('notification.recipient')" :options="recipientTypes" v-model="storeAndUpdateParams.user_type"/>
                <text-input v-if="storeAndUpdateParams.user_type" id="user_id" :title="t('global.user_id')" number-type="int" :min="1" :disabled="loading" v-model="storeAndUpdateParams.user_id"/>
                <p class="text-sm text-gray-600">{{ t('notification.global_hint') }}</p>
                <text-input id="url" :title="t('notification.url')" inputmode="url" :maxlength="2048" :disabled="loading" v-model="storeAndUpdateParams.url"/>
                <p class="text-sm text-gray-600">{{ t('notification.url_hint') }}</p>
                <app-button type="submit" :loading="pending" :disabled="loading" class="w-full">{{ t(updateMode ? 'global.update' : 'notification.send') }}</app-button>
            </form>
        </card>
    </div>
</template>

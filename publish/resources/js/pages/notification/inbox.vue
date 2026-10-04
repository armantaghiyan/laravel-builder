<script setup lang="ts">
import useNotificationInbox from "@/composables/notification/useNotificationInbox.ts";
import useNotificationRead from "@/composables/notification/useNotificationRead.ts";
import NotificationLink from "@/components/fragments/notification/notification-link.vue";
import {Permissions} from "@/utils/models/enums.ts";
import {NotificationInboxItem} from "@/utils/models/Notification.ts";

const {t} = useTranslations();
const {hasPermission} = usePermission();
const {fetchData, reFetchData, items, count, params, loading} = useNotificationInbox();
const {markRead, pending} = useNotificationRead();
const route = useRoute();
const readOptions = computed(() => [
    {value: 0, label: t('notification.unread')},
    {value: 1, label: t('notification.read')},
]);

async function readItem(item: NotificationInboxItem) {
    await markRead(item);
    if (params.is_read === 0 && items.value.length === 1 && params.page > 1) params.page--;
    await fetchData();
}

onMounted(fetchData);
</script>

<template>
    <card :title="t('notification.my_notifications')">
        <action-content>
            <div class="flex gap-4 items-end">
                <select-input :title="t('notification.read_status')" :options="readOptions" with-all v-model="params.is_read" class="w-40"/>
                <app-button :loading="loading" @click="reFetchData">{{ t('notification.refresh') }}</app-button>
            </div>
            <div class="flex gap-4">
                <page-rows v-model="params.page_rows"/>
                <router-link v-if="hasPermission(Permissions.NOTIFICATION_INDEX)" to="/notification"><app-button>{{ t('menu.notifications') }}</app-button></router-link>
            </div>
        </action-content>
        <div class="px-6 pb-6 flex flex-col gap-3" :aria-busy="loading">
            <p v-if="!loading && !items.length" class="py-6 text-center text-gray-600">{{ t('global.no_results') }}</p>
            <article v-for="item in items" :key="item.id" class="rounded-md border border-gray-200 p-4" :class="{'bg-light-primary': !item.is_read, 'ring-2 ring-primary': String(item.id) === route.query.id}">
                <div class="flex items-start justify-between gap-3">
                    <h3 class="font-medium break-words">{{ item.title }}</h3>
                    <span class="text-xs whitespace-nowrap text-gray-600">{{ t(item.is_read ? 'notification.read' : 'notification.unread') }}</span>
                </div>
                <p class="whitespace-pre-wrap break-words mt-2">{{ item.message }}</p>
                <div class="flex flex-wrap items-center justify-between gap-3 mt-4 text-sm">
                    <div class="flex flex-wrap gap-3 text-gray-600"><span dir="ltr">{{ item.created_at }}</span><span v-if="item.is_global">{{ t('notification.global') }}</span></div>
                    <div class="flex flex-wrap items-center gap-3">
                        <notification-link v-if="item.url" :url="item.url" @click="!item.is_read && markRead(item)"/>
                        <app-button v-if="!item.is_read" size="sm" :loading="pending" @click="readItem(item)">{{ t('notification.mark_read') }}</app-button>
                    </div>
                </div>
            </article>
        </div>
        <pagination v-model="params.page" :count="count" :page-rows="params.page_rows" @change="fetchData"/>
    </card>
</template>

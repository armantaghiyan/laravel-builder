<script setup lang="ts">
import useNotificationShow from "@/composables/notification/useNotificationShow.ts";
import useNotificationDestroy from "@/composables/notification/useNotificationDestroy.ts";
import NotificationLink from "@/components/fragments/notification/notification-link.vue";
import {Permissions} from "@/utils/models/enums.ts";

const {t} = useTranslations();
const {hasPermission} = usePermission();
const route = useRoute();
const router = useRouter();
const {show, item} = useNotificationShow();
const {destroy} = useNotificationDestroy();

function destroyItem() {
    destroy(route.params.id as string, () => router.replace('/notification'));
}

onMounted(() => show(route.params.id as string));
</script>

<template>
    <card :title="t('notification.details')" class="detail-surface">
        <template #header>
            <option-menu v-if="hasPermission(Permissions.NOTIFICATION_UPDATE) || hasPermission(Permissions.NOTIFICATION_DESTROY)" :width="240" :top="50">
                <template #button><btn-option/></template>
                <div class="flex flex-col p-2 gap-1">
                    <router-link v-if="hasPermission(Permissions.NOTIFICATION_UPDATE)" :to="`/notification/create/${route.params.id}`"><btn-clickable>{{ t('app.edit') }}</btn-clickable></router-link>
                    <btn-clickable v-if="hasPermission(Permissions.NOTIFICATION_DESTROY)" @click="destroyItem">{{ t('app.delete') }}</btn-clickable>
                </div>
            </option-menu>
        </template>
        <div v-if="item" class="grid md:grid-cols-2 grid-cols-1 gap-x-4 gap-y-1 px-6 pb-6">
            <label-item icon="ti-hash" :title="t('global.id')">{{ item.id }}</label-item>
            <label-item icon="ti-bell" :title="t('notification.title')">{{ item.title }}</label-item>
            <label-item icon="ti-user" :title="t('notification.recipient')">
                <span v-if="item.is_global">{{ t(`notification.all_${item.user_type}s`) }}</span>
                <span v-else>{{ t(`notification.${item.user_type}`) }}: {{ item.user_name || item.user_id }} <span v-if="item.user_name">(#{{ item.user_id }})</span></span>
            </label-item>
            <label-item icon="ti-checks" :title="t('notification.read_status')">
                {{ t(item.is_read ? 'notification.read' : 'notification.unread') }}
            </label-item>
            <label-item icon="ti-calendar-plus" :title="t('global.created_at')"><span dir="ltr">{{ item.created_at }}</span></label-item>
            <label-item icon="ti-calendar-event" :title="t('global.updated_at')"><span dir="ltr">{{ item.updated_at }}</span></label-item>
            <label-item icon="ti-message" :title="t('global.message')" class="md:col-span-2"><span class="whitespace-pre-wrap break-words">{{ item.message }}</span></label-item>
            <label-item v-if="item.url" icon="ti-link" :title="t('notification.url')" class="md:col-span-2"><notification-link :url="item.url">{{ item.url }}</notification-link></label-item>
        </div>
    </card>
</template>

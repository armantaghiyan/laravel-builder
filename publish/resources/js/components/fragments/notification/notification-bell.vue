<script setup lang="ts">
import {MenuItem} from '@headlessui/vue';
import useNotificationInbox from "@/composables/notification/useNotificationInbox.ts";
import useNotificationRead from "@/composables/notification/useNotificationRead.ts";
import {notificationStore} from "@/stores/notification.ts";
import {NotificationInboxItem} from "@/utils/models/Notification.ts";

const {t} = useTranslations();
const $user = userStore();
const $notification = notificationStore();
const {fetchData, items, loading} = useNotificationInbox();
const {markRead, pending} = useNotificationRead();
const router = useRouter();
const isSm = useBreakpoint('sm');

function refresh() {
    if ($user.isAuth && !loading.value) {
        fetchData().catch(() => {});
    }
}

async function openNotification(item: NotificationInboxItem) {
    if (pending.value) return;
    try {
        if (!item.is_read) await markRead(item);
        await router.push({path: '/notification/inbox', query: {id: item.id}});
    } catch {}
}

watch(() => $user.user.id, () => {
    items.value = [];
    $notification.unreadCount = 0;
    refresh();
}, {immediate: true});
</script>

<template>
    <option-menu :width="isSm ? 320 : 240" :top="48">
        <template #button>
            <span class="relative size-10 rounded-full hover:bg-panel cursor-pointer flex items-center justify-center" @click="refresh">
                <span class="sr-only">{{ t('notification.my_notifications') }}</span>
                <i class="ti ti-bell ti-md" aria-hidden="true"></i>
                <span v-if="$notification.unreadCount" class="absolute -top-1 -end-1 min-w-5 h-5 px-1 rounded-full bg-danger text-white text-xs flex items-center justify-center" aria-live="polite">
                    {{ $notification.unreadCount > 99 ? '99+' : $notification.unreadCount }}
                </span>
            </span>
        </template>

        <div class="border-b border-gray-200 px-4 py-3 font-medium">{{ t('notification.my_notifications') }}</div>
        <div class="max-h-96 overflow-y-auto">
            <p v-if="loading" class="p-4 text-sm text-gray-600">{{ t('notification.loading') }}</p>
            <p v-else-if="!items.length" class="p-4 text-sm text-gray-600">{{ t('global.no_results') }}</p>
            <MenuItem v-for="item in items" :key="item.id" v-slot="{active}">
                <button type="button" :disabled="pending" class="w-full text-start border-b border-gray-200 p-4" :class="{'bg-light-primary': active || !item.is_read}" @click="openNotification(item)">
                    <span class="flex items-center justify-between gap-2">
                        <span class="font-medium">{{ item.title }}</span>
                        <span v-if="!item.is_read" class="size-2 rounded-full bg-primary shrink-0" :aria-label="t('notification.unread')"></span>
                    </span>
                    <span class="block text-sm text-gray-600 whitespace-pre-wrap break-words mt-1">{{ item.message }}</span>
                    <span class="block text-xs text-gray-600 mt-2" dir="ltr">{{ item.created_at }}</span>
                </button>
            </MenuItem>
        </div>
        <MenuItem v-slot="{active}">
            <router-link to="/notification/inbox" class="block p-3 text-center text-sm text-primary" :class="{'bg-light-primary': active}">
                {{ t('notification.view_all') }}
            </router-link>
        </MenuItem>
    </option-menu>
</template>

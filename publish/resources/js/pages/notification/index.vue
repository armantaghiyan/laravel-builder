<script setup lang="ts">
import useNotificationList from "@/composables/notification/useNotificationList.ts";
import useNotificationDestroy from "@/composables/notification/useNotificationDestroy.ts";
import Notification from "@/utils/models/Notification.ts";
import {Permissions} from "@/utils/models/enums.ts";

const {t} = useTranslations();
const {hasPermission} = usePermission();
const {fetchData, reFetchData, items, count, params, loading} = useNotificationList();
const {destroy} = useNotificationDestroy();
const recipientTypes = computed(() => [
    {value: 'admin', label: t('notification.admin')},
    {value: 'user', label: t('notification.user')},
]);
const audiences = computed(() => [
    {value: 1, label: t('notification.global')},
    {value: 0, label: t('notification.personal')},
]);

function destroyItem(item: Notification) {
    destroy(item.id, () => {
        if (items.value.length === 1 && params.page > 1) params.page--;
        fetchData();
    });
}

onActivated(fetchData);
</script>

<template>
    <card :title="t('menu.notifications')">
        <form @submit.prevent="reFetchData">
            <filter-content>
                <text-input :title="t('global.id')" v-model="params.id"/>
                <text-input :title="t('notification.title')" v-model="params.title"/>
                <select-input :title="t('notification.audience')" :options="audiences" with-all v-model="params.is_global"/>
                <select-input :title="t('notification.user_type')" :options="recipientTypes" with-all v-model="params.user_type"/>
                <text-input :title="t('global.user_id')" number-type="int" v-model="params.user_id"/>
            </filter-content>
            <action-content>
                <div class="flex gap-4">
                    <text-input :placeholder="t('global.search')" class="sm:w-50 w-full" v-model="params.search"/>
                    <btn-search :loading="loading"/>
                </div>
                <div class="flex gap-4">
                    <page-rows v-model="params.page_rows"/>
                    <router-link v-if="hasPermission(Permissions.NOTIFICATION_STORE)" to="/notification/create"><btn-primary-add/></router-link>
                </div>
            </action-content>
        </form>
        <custom-table :loading="loading">
            <custom-thead>
                <custom-tr>
                    <custom-th fixed :width="100" sort-key="id" v-model:sort="params.sort" v-model:sort-type="params.sort_type">{{ t('global.id') }}</custom-th>
                    <custom-th :width="240" sort-key="title" v-model:sort="params.sort" v-model:sort-type="params.sort_type">{{ t('notification.title') }}</custom-th>
                    <custom-th :width="200">{{ t('notification.recipient') }}</custom-th>
                    <custom-th :width="140">{{ t('notification.read_status') }}</custom-th>
                    <custom-th :width="180" sort-key="created_at" v-model:sort="params.sort" v-model:sort-type="params.sort_type">{{ t('global.created_at') }}</custom-th>
                    <custom-th fixed :width="120">{{ t('global.actions') }}</custom-th>
                </custom-tr>
            </custom-thead>
            <custom-tbody>
                <custom-tr v-for="item in items" :key="item.id" class="border-b border-gray-200">
                    <custom-td :copy="item.id"><id-formater :id="item.id"/></custom-td>
                    <custom-td>{{ item.title }}</custom-td>
                    <custom-td>
                        <span v-if="item.is_global">{{ t('notification.global') }}</span>
                        <span v-else>{{ t(`notification.${item.user_type}`) }}: {{ item.user_name || item.user_id }} <span v-if="item.user_name">(#{{ item.user_id }})</span></span>
                    </custom-td>
                    <custom-td>
                        <span>{{ t(item.is_read ? 'notification.read' : 'notification.unread') }}</span>
                    </custom-td>
                    <custom-td><span dir="ltr">{{ item.created_at }}</span></custom-td>
                    <custom-td class="flex">
                        <btn-delete v-if="hasPermission(Permissions.NOTIFICATION_DESTROY)" @click="destroyItem(item)"/>
                        <btn-see :href="`/notification/${item.id}`"/>
                        <router-link v-if="hasPermission(Permissions.NOTIFICATION_UPDATE)" :to="`/notification/create/${item.id}`"><btn-edit/></router-link>
                    </custom-td>
                </custom-tr>
            </custom-tbody>
        </custom-table>
        <pagination v-model="params.page" :count="count" :page-rows="params.page_rows" @change="fetchData"/>
    </card>
</template>

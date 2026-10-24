<script setup lang="ts">
import useAdminSessionList from "@/composables/admin/useAdminSessionList.ts";
import useAdminSessionDestroy from "@/composables/admin/useAdminSessionDestroy.ts";
import AdminSession from "@/utils/models/AdminSession.ts";

const props = withDefaults(defineProps<{
    adminId?: string | number;
    canDestroy?: boolean;
}>(), {
    canDestroy: true,
});

const {t} = useTranslations();
const {fetchData, items, loading} = useAdminSessionList();
const {destroy} = useAdminSessionDestroy();
const emit = defineEmits<{count: [value: number]}>();

function fetchSessions() {
    return fetchData(props.adminId).then(() => {
        emit('count', items.value.length);
    });
}

function destroyItem(item: AdminSession) {
    destroy(item.id, props.adminId, fetchSessions);
}

onMounted(fetchSessions);
</script>

<template>
    <card :title="t('admin.active_sessions')" class="detail-surface">
        <custom-table :loading="loading">
            <custom-thead>
                <custom-tr>
                    <custom-th :width="100">{{ t('global.id') }}</custom-th>
                    <custom-th :width="160">{{ t('global.status') }}</custom-th>
                    <custom-th :width="180">{{ t('global.created_at') }}</custom-th>
                    <custom-th :width="180">{{ t('admin.last_activity') }}</custom-th>
                    <custom-th :width="180">{{ t('admin.session_expires_at') }}</custom-th>
                    <custom-th :width="100">{{ t('global.actions') }}</custom-th>
                </custom-tr>
            </custom-thead>
            <custom-tbody>
                <custom-tr v-for="item in items" :key="item.id" class="border-b border-gray-200">
                    <custom-td><id-formater :id="item.id"/></custom-td>
                    <custom-td>
                        <badge v-if="item.is_current" theme="success">{{ t('admin.current_session') }}</badge>
                        <span v-else>-</span>
                    </custom-td>
                    <custom-td><span dir="ltr">{{ item.created_at }}</span></custom-td>
                    <custom-td><span dir="ltr">{{ item.last_used_at || '-' }}</span></custom-td>
                    <custom-td><span dir="ltr">{{ item.expires_at || '-' }}</span></custom-td>
                    <custom-td>
                        <btn-delete v-if="canDestroy && !item.is_current" @click="destroyItem(item)"/>
                    </custom-td>
                </custom-tr>
            </custom-tbody>
        </custom-table>
    </card>
</template>

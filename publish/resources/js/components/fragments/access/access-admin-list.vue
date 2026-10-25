<script setup lang="ts">
import {AccessAdminIndexResponse} from "@/utils/api/access.ts";
import {Permissions} from "@/utils/models/enums.ts";

defineProps<{
    items: AccessAdminIndexResponse['data']['items'];
    count: number;
    pageRows: number;
    loading: boolean;
    failed: boolean;
}>();

const page = defineModel<number>({default: 1});
const emit = defineEmits<{change: []; retry: []}>();
const {t} = useTranslations();
const {hasPermission} = usePermission();
</script>

<template>
    <div :aria-busy="loading">
        <div v-if="failed" role="alert" class="flex flex-wrap items-center justify-between gap-3 py-4 text-sm text-gray-600">
            <span>{{ t('access.admins_failed') }}</span>
            <app-button outline @click="emit('retry')">{{ t('access.retry') }}</app-button>
        </div>
        <template v-else>
            <p v-if="loading" role="status" class="pb-4 text-sm text-gray-500">{{ t('access.loading_admins') }}</p>
            <custom-table :loading="loading">
                <custom-thead>
                    <custom-tr>
                        <custom-th :width="100">{{ t('global.id') }}</custom-th>
                        <custom-th>{{ t('global.name') }}</custom-th>
                        <custom-th>{{ t('global.username') }}</custom-th>
                        <custom-th v-if="hasPermission(Permissions.ADMIN_INDEX)" :width="100">{{ t('global.actions') }}</custom-th>
                    </custom-tr>
                </custom-thead>
                <custom-tbody>
                    <custom-tr v-for="item in items" :key="item.id">
                        <custom-td><id-formater :id="item.id"/></custom-td>
                        <custom-td>{{ item.name || '-' }}</custom-td>
                        <custom-td><span dir="ltr">{{ item.username }}</span></custom-td>
                        <custom-td v-if="hasPermission(Permissions.ADMIN_INDEX)">
                            <btn-see :href="`/admin/${item.id}`" :aria-label="t('access.view_admin')" :title="t('access.view_admin')"/>
                        </custom-td>
                    </custom-tr>
                </custom-tbody>
            </custom-table>
            <pagination v-model="page" :count="count" :page-rows="pageRows" :loading="loading" @change="emit('change')"/>
        </template>
    </div>
</template>

<script setup lang="ts">
import useAccessRoleAdmins from "@/composables/access/useAccessRoleAdmins.ts";
import AccessAdminList from "@/components/fragments/access/access-admin-list.vue";

const props = defineProps<{roleId: number}>();
const {fetchData, items, count, params, loading, failed} = useAccessRoleAdmins();
const {t} = useTranslations();

function fetchAdmins() {
    return fetchData(props.roleId);
}

onMounted(fetchAdmins);
</script>

<template>
    <card :title="t('access.role_admins')" class="detail-surface">
        <div class="px-6 pb-6">
            <access-admin-list v-model="params.page" :items="items" :count="count" :page-rows="params.page_rows" :loading="loading" :failed="failed" @change="fetchAdmins" @retry="fetchAdmins"/>
        </div>
    </card>
</template>

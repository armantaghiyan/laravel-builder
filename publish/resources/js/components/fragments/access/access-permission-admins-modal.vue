<script setup lang="ts">
import {Dialog, DialogPanel, DialogTitle} from "@headlessui/vue";
import Permission from "@/stores/Permission.ts";
import useAccessPermissionAdmins from "@/composables/access/useAccessPermissionAdmins.ts";
import AccessAdminList from "@/components/fragments/access/access-admin-list.vue";

const props = defineProps<{permission: Permission}>();
const emit = defineEmits<{close: []}>();
const {fetchData, items, count, params, loading, failed} = useAccessPermissionAdmins();
const {t} = useTranslations();

function fetchAdmins() {
    return fetchData(props.permission.id);
}

onMounted(fetchAdmins);
</script>

<template>
    <Dialog :open="true" class="relative z-[110]" @close="emit('close')">
        <div class="fixed inset-0 bg-black/40" aria-hidden="true"/>
        <div class="fixed inset-0 overflow-y-auto p-4">
            <div class="flex min-h-full items-center justify-center">
                <DialogPanel class="w-full max-w-3xl rounded-2xl bg-white p-6 shadow-xl">
                    <div class="mb-6 flex items-start justify-between gap-4">
                        <div>
                            <DialogTitle class="font-bold">{{ t('access.permission_admins') }}</DialogTitle>
                            <p class="mt-2 text-sm text-gray-600">{{ t(`access.permission.${permission.name}`) }}</p>
                        </div>
                        <app-button outline size="sm" @click="emit('close')">{{ t('access.close') }}</app-button>
                    </div>
                    <access-admin-list v-model="params.page" :items="items" :count="count" :page-rows="params.page_rows" :loading="loading" :failed="failed" @change="fetchAdmins" @retry="fetchAdmins"/>
                </DialogPanel>
            </div>
        </div>
    </Dialog>
</template>

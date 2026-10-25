<script setup>
import {Permissions} from "@/utils/models/enums.ts";
import AccessRoleAdminsFragment from "@/components/fragments/access/access-role-admins-fragment.vue";
import AccessPermissionAdminsModal from "@/components/fragments/access/access-permission-admins-modal.vue";

const { show, rolePermissions, rolePermissionLoaded } = useAccess();

const route = useRoute();
const $user = userStore();
const {t} = useTranslations();
const {hasPermission} = usePermission();
const activeTab = ref('permissions');
const selectedPermission = ref(null);
const roleId = computed(() => Number(route.params.id));

watch(roleId, () => {
    activeTab.value = 'permissions';
    selectedPermission.value = null;
    rolePermissionLoaded.value = false;
    show(route.params.id);
}, {immediate: true});

const groupedPermissions = computed(() => {
    const groups = {};

    for (const permission of $user.permissions) {
        const [prefix] = permission.name.split('.');

        if (!groups[prefix]) {
            groups[prefix] = [];
        }

        groups[prefix].push(permission);
    }

    return groups;
});
</script>

<template>
    <div class="flex flex-col gap-6">
        <tab-bar v-model="activeTab" :tabs="[
            {key: 'permissions', label: t('access.permissions')},
            ...(hasPermission(Permissions.ADMIN_INDEX) ? [{key: 'admins', label: t('access.role_admins')}] : []),
        ]"/>

        <card v-show="activeTab === 'permissions'" :title="t('access.permissions')" class="detail-surface">
            <div v-if="rolePermissionLoaded" class="px-6 pb-6 grid md:grid-cols-2 grid-cols-1 gap-6">
                <div v-for="(permissions, group) in groupedPermissions" :key="group">
                    <h3 class="font-bold mb-2">{{ t(`roles.${group}`) }}</h3>
                    <permission-adapter
                        v-for="permission in permissions"
                        :key="`${roleId}-${permission.id}`"
                        :permission="permission"
                        :role-permissions="rolePermissions"
                        :role-id="roleId"
                        @show-admins="selectedPermission = $event"
                    />
                </div>
            </div>
        </card>
        <access-role-admins-fragment v-if="activeTab === 'admins' && hasPermission(Permissions.ADMIN_INDEX)" :key="roleId" :role-id="roleId"/>
        <access-permission-admins-modal v-if="selectedPermission && hasPermission(Permissions.ADMIN_INDEX)" :key="selectedPermission.id" :permission="selectedPermission" @close="selectedPermission = null"/>
    </div>
</template>

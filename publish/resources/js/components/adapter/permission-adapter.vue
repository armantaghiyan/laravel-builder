<script setup lang="ts">
import Permission from "@/stores/Permission.ts";
import {Permissions} from "@/utils/models/enums.ts";

const props = defineProps<{
    roleId: number,
    permission: Permission,
    rolePermissions: Permission[],
}>();

const {togglePermission} = useAccess();
const {hasPermission} = usePermission();
const emit = defineEmits<{showAdmins: [permission: Permission]}>();
const active = ref(false);
const canToggle = ref(true);
const {t} = useTranslations();

function isActive() {
    if (props.rolePermissions.find(rolePermission => rolePermission.id === props.permission.id)) {
        active.value = true;
    }
}

async function doTogglePermission() {
    canToggle.value = false;

    try {
        await togglePermission(props.permission.id, props.roleId);
    } catch {
        active.value = !active.value;
    } finally {
        canToggle.value = true;
    }
}

onMounted(() => {
    isActive();
});
</script>

<template>
    <switch-input :title="t(`access.permission.${permission.name}`)" :disabled="!canToggle || !hasPermission(Permissions.ROLE_UPDATE)" v-model="active" @on-click="doTogglePermission">
        <template #title>
            <button v-if="hasPermission(Permissions.ADMIN_INDEX)" type="button" class="cursor-pointer text-start text-primary hover:underline focus-visible:underline" aria-haspopup="dialog" @click="emit('showAdmins', permission)">
                {{ t(`access.permission.${permission.name}`) }}
            </button>
            <span v-else>{{ t(`access.permission.${permission.name}`) }}</span>
        </template>
    </switch-input>
</template>

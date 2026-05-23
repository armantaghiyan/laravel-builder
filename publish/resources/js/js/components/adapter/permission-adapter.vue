<script setup lang="ts">
import {useAccess} from "@/composables/access/useAccess.ts";
import Permission from "@/stores/Permission.ts";
import {onMounted, ref} from "vue";
import {showLoading} from "@/utils/helper.ts";
import {useTranslations} from "@/composables/useTranslations.ts";

const props = defineProps<{
    roleId: number,
    permission: Permission,
    rolePermissions: Permission[],
}>();

const {togglePermission} = useAccess();
const active = ref(false);
const canToggle = ref(true);
const {t} = useTranslations();

function isActive() {
    if (props.rolePermissions.find(rolePermission => rolePermission.id === props.permission.id) || props.roleId == 1) {
        active.value = true;
    }
}

function doTogglePermission() {
    canToggle.value = false;
    showLoading();
    togglePermission(props.permission.id, props.roleId, () => {
        canToggle.value = true;
    });
}

onMounted(() => {
    isActive();
});
</script>

<template>
    <switch-input :title="t(`access.permission.${permission.name}`)" v-model="active" @on-click="doTogglePermission" :disabled="props.roleId == 1"/>
</template>

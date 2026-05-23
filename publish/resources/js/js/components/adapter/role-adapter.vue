<script setup lang="ts">
import {onMounted, ref} from "vue";
import {useAccess} from "@/composables/access/useAccess.ts";
import Role from "@/utils/models/Role.ts";
import {showLoading} from "@/utils/helper.ts";
import {useTranslations} from "@/composables/useTranslations.ts";

const props = defineProps<{
    adminId: number
    role: Role
    adminRoles: Role[]
}>();

const {toggleAccess} = useAccess();
const active = ref(false);
const canToggle = ref(true);
const {t} = useTranslations();

function isActive() {
    if (props.adminRoles.find(adminRole => adminRole.id === props.role.id)) {
        active.value = true;
    }
}

function toggleRole() {
    canToggle.value = false;
    showLoading();
    toggleAccess(props.role.id, props.adminId, () => {
        canToggle.value = true;
    });
}

onMounted(() => {
    isActive();
});
</script>

<template>
    <switch-input :title="t(`roles.${role.name}`)" :href="`/access/${role.id}`" v-model="active" @on-click="toggleRole"/>
</template>

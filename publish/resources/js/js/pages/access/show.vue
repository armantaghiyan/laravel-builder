<script setup>
import {userStore} from "@/stores/user.js";
import {computed, onMounted} from "vue";
import {useRoute} from "vue-router";
import {useAccess} from "@/composables/access/useAccess.js";
import {useTranslations} from "@/composables/useTranslations.js";

const { show, rolePermissions, rolePermissionLoaded } = useAccess();

const route = useRoute();
const $user = userStore();
const {t} = useTranslations();

onMounted(() => {
    show(route.params.id);
});

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
    <card :title="t('access.permissions')">
        <div v-if="rolePermissionLoaded" class="px-6 pb-6 grid md:grid-cols-2 grid-cols-1 gap-6">
            <div v-for="(permissions, group) in groupedPermissions" :key="group">
                <h3 class="font-bold mb-2">{{ t(`roles.${group}`) }}</h3>
                <permission-adapter
                    v-for="permission in permissions"
                    :key="permission.id"
                    :permission="permission"
                    :role-permissions="rolePermissions"
                    :role-id="route.params.id"
                />
            </div>
        </div>
    </card>
</template>

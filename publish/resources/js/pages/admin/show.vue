<script setup>
import {Permissions} from "@/utils/models/enums.ts";
import useAdmin from "@/composables/admin/useAdmin.js";
import {usePermission} from "@/composables/usePermission.js";
import {useTranslations} from "@/composables/useTranslations.js";

const {show, item, adminRoles, roles} = useAdmin();
const {hasPermission} = usePermission();

const route = useRoute();
const {t} = useTranslations();

onMounted(() => {
    show(route.params.id);
});
</script>

<template>
    <div class="flex flex-col gap-6">
        <card>
            <div
                class="bg-[url('/resources/assets/images/bg/profile-banner.jpg')] bg-cover bg-center md:h-[250px] h-[150px] rounded-t-[6px]"></div>

            <div class="relative">
                <div class="flex items-end gap-6 absolute top-[-50px] h-30 px-6">
                    <div class="border-5 border-white md:w-[120px] w-[100px] rounded-[2px]">
                        <img src="/resources/assets/images/icon/user.jpg" class="w-full h-full" alt="">
                    </div>

                    <div class="flex flex-col gap-3">
                        <div class="text-[24px]">{{ item?.name }}</div>
                        <div class="text-gray-9">@{{ item?.username }}</div>
                    </div>
                </div>
            </div>

            <div class="h-22"></div>
        </card>

        <div class="grid grid-cols-12 gap-6">
            <card class="lg:col-span-4 col-span-12 p-6">
                <label-title>{{ t('admin.admin_detail') }}</label-title>

                <label-item icon="ti-hash" :title="t('global.id')">{{ item?.id }}</label-item>

                <label-item icon="ti-user" :title="t('global.name')">{{ item?.name }}</label-item>

                <label-item icon="ti-at" :title="t('global.username')">{{ item?.username }}</label-item>

                <label-item icon="ti-login" :title="t('admin.last_login')">
                    <span dir="ltr">{{ item?.last_login }}</span>
                </label-item>

                <label-item icon="ti-calendar-plus" :title="t('global.created_at')">
                    <span dir="ltr">{{ item?.created_at }}</span>
                </label-item>

                <label-item icon="ti-calendar-event" :title="t('global.updated_at')">
                    <span dir="ltr">{{ item?.updated_at }}</span>
                </label-item>
            </card>
            <card v-if="hasPermission(Permissions.ADMIN_ADD_ROLE)" :title="t('roles.role')" class="lg:col-span-8 col-span-12">

                <div class="px-6 pb-6 grid grid-cols-2 gap-6">
                    <role-adapter v-for="role in roles" :role="role" :adminRoles="adminRoles" :adminId="item.id"/>
                </div>

            </card>
        </div>
    </div>
</template>

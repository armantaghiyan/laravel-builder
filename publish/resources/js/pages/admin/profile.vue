<script setup lang="ts">
import {toast} from "@/utils/toastify.ts";

const {t} = useTranslations();
const {profileItem, fetchProfile, passwordParams, changePassword, pending} = useAdmin();

function submitPassword() {
    if (passwordParams.new_password !== passwordParams.new_password_confirmation) {
        errorToast(t('auth.passwords_do_not_match'));
        return;
    }

    changePassword().then(() => {
        toast(t('admin.password_updated'));
    });
}

onMounted(fetchProfile);
</script>

<template>
    <div class="flex flex-col gap-6">
        <card class="overflow-hidden detail-surface">
            <div class="bg-[url('/resources/assets/images/bg/profile-banner.jpg')] bg-cover bg-center md:h-[250px] h-[150px] rounded-t-2xl"></div>

            <div class="relative">
                <div class="flex items-end gap-6 absolute top-[-50px] h-30 px-6">
                    <div class="border-5 border-white md:w-[120px] w-[100px] rounded-2xl shadow-xl overflow-hidden">
                        <img src="/resources/assets/images/icon/user.jpg" class="w-full h-full" alt="">
                    </div>

                    <div class="flex flex-col gap-3">
                        <div class="text-[24px] font-bold">{{ profileItem?.name }}</div>
                        <div class="text-gray-500">@{{ profileItem?.username }}</div>
                    </div>
                </div>
            </div>

            <div class="h-22"></div>
        </card>

        <div class="grid grid-cols-12 gap-6">
            <card :title="t('admin.admin_detail')" class="lg:col-span-5 col-span-12 detail-surface">
                <div class="px-6 pb-6">
                    <label-item icon="ti-hash" :title="t('global.id')">{{ profileItem?.id }}</label-item>
                    <label-item icon="ti-user" :title="t('global.name')">{{ profileItem?.name }}</label-item>
                    <label-item icon="ti-at" :title="t('global.username')">{{ profileItem?.username }}</label-item>
                    <label-item icon="ti-login" :title="t('admin.last_login')"><span dir="ltr">{{ profileItem?.last_login }}</span></label-item>
                    <label-item icon="ti-calendar-plus" :title="t('global.created_at')"><span dir="ltr">{{ profileItem?.created_at }}</span></label-item>
                </div>
            </card>

            <card :title="t('admin.change_password')" class="lg:col-span-7 col-span-12 detail-surface">
                <div class="px-6 pb-6 flex flex-col gap-5">
                    <text-input id="old_password" :title="t('admin.current_password')" type="password" v-model="passwordParams.old_password"/>
                    <text-input id="new_password" :title="t('auth.password')" type="password" v-model="passwordParams.new_password"/>
                    <text-input id="new_password_confirmation" :title="t('auth.repeat_password')" type="password" v-model="passwordParams.new_password_confirmation"/>
                    <app-button @click="submitPassword" :loading="pending" class="w-full">{{ t('admin.change_password') }}</app-button>
                </div>
            </card>
        </div>
    </div>
</template>

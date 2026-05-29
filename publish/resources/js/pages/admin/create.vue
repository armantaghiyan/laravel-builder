<script setup lang="ts">
import Admin from "@/utils/models/Admin.ts";

const {storeAndUpdateParams, update, store, show} = useAdmin();

const route = useRoute();
const {t} = useTranslations();

const updateMode = ref(false);

function submitForm(){
    if(storeAndUpdateParams.password !== storeAndUpdateParams.repeat_password){
        errorToast("رمز های عبور با هم برابر نیستند");
        return;
    }

    if(updateMode.value){
        update(route.params.id as string);
    }else {
        store();
    }
}

onMounted(() => {
    if(route.params.id){
        updateMode.value = true;
        show(route.params.id as string, (admin: Admin) => {
            storeAndUpdateParams.id = admin.id.toString();
            storeAndUpdateParams.name = admin.name;
            storeAndUpdateParams.username = admin.username;
        });
    }
});
</script>

<template>
    <div>
        <div class="pb-6 pt-2">
            <h4 class="font-medium text-[24px] pb-2">{{t('admin.add_a_admin')}}</h4>
        </div>

        <card :title="t('admin.admin_information')">
            <div class="px-6 pb-6 flex flex-col gap-6">
                <text-input id="name" :title="t('global.name')" v-model="storeAndUpdateParams.name"/>
                <text-input id="username" :title="t('global.username')" v-model="storeAndUpdateParams.username"/>

                <div v-if="!updateMode" class="grid grid-cols-2 gap-6">
                    <text-input id="password" :title="t('auth.password')" type="password" v-model="storeAndUpdateParams.password"/>
                    <text-input :title="t('auth.repeat_password')" type="password" v-model="storeAndUpdateParams.repeat_password"/>
                </div>

                <btn-primary @click="submitForm" class="w-full">{{t('global.submit')}}</btn-primary>
            </div>
        </card>
    </div>
</template>

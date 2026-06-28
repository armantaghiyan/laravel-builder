<script setup lang="ts">
import Role from "@/utils/models/Role.ts";

const {storeAndUpdateParams, update, store, showRole} = useAccess();

const route = useRoute();
const updateMode = ref(false);
const {t} = useTranslations();


function submitForm(){
    if(updateMode.value){
        update(route.params.id as string);
    }else {
        store();
    }
}

onMounted(() => {
    if(route.params.id){
        updateMode.value = true;
        showRole(route.params.id as string, (role: Role) => {
            storeAndUpdateParams.id = role.id.toString();
            storeAndUpdateParams.name = role.name;
        });
    }
});
</script>

<template>
    <div>
        <div class="pb-6 pt-2">
            <h4 class="font-medium text-[24px] pb-2">{{t('roles.add_a_role')}}</h4>
        </div>

        <card :title="t('roles.role_information')">
            <div class="px-6 pb-6 flex flex-col gap-6">
                <text-input id="name" :title="t('global.name')" v-model="storeAndUpdateParams.name"/>

                <app-button @click="submitForm" class="w-full">{{t('global.submit')}}</app-button>
            </div>
        </card>
    </div>
</template>

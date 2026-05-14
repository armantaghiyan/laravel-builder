<script setup lang="ts">
import {useAccess} from "@/composables/access/useAccess.ts";
import {useRoute} from "vue-router";
import {onMounted, ref} from "vue";
import Role from "@/utils/models/Role.ts";

const {storeAndUpdateParams, update, store, showRole} = useAccess();

const route = useRoute();
const updateMode = ref(false);

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
            <h4 class="font-medium text-[24px] pb-2">{{$t('admin.add_a_admin')}}</h4>
        </div>

        <card :title="$t('admin.admin_information')">
            <div class="px-6 pb-6 flex flex-col gap-6">
                <text-input id="name" :title="$t('global.name')" v-model="storeAndUpdateParams.name"/>

                <btn-primary @click="submitForm" class="w-full">{{$t('global.submit')}}</btn-primary>
            </div>
        </card>
    </div>
</template>

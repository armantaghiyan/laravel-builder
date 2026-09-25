<script setup>
import useLogShow from "@/composables/log/useLogShow.js";
import {usePermission} from "@/composables/usePermission.ts";
import {Permissions} from "@/utils/models/enums.ts";
import useLogUpdateReviewed from "@/composables/log/useLogUpdateReviewed.ts";

const {show, item} = useLogShow();
const {updateReviewed} = useLogUpdateReviewed();
const {hasPermission} = usePermission();


const route = useRoute();
const router = useRouter();
const {t} = useTranslations();

function toggleReviewed() {
    if (!item.value) {
        return;
    }

    updateReviewed(item.value.id, Number(item.value.is_reviewed) === 1 ? 0 : 1);
    show(route.params.id);
}

onMounted(() => {
    show(route.params.id);
});
</script>

<template>
    <card :title="t('log.name_detail')" class="col-span-12 detail-surface">
        <template #header>
            <option-menu v-if="hasPermission(Permissions.LOG_UPDATE)" :width="240" :top="50" position="auto">
                <template #button>
                    <btn-option/>
                </template>
                <div v-if="item" class="flex flex-col p-2 gap-1">
                    <btn-clickable @click="toggleReviewed">
                        {{ Number(item.is_reviewed) === 1 ? t('log.mark_as_not_reviewed') : t('log.mark_as_reviewed') }}
                    </btn-clickable>
                </div>
            </option-menu>
        </template>

        <div class="grid md:grid-cols-2 grid-cols-1 px-6 pb-6">
            <label-item icon="ti-hash" :title="t('global.id')">{{item?.id}}</label-item>
            <label-item icon="ti-user" :title="t('global.user_guard')">
                <badge v-if="item?.user_guard" :theme="item?.user_guard_color">{{item?.user_guard_text}} - {{item?.user_id}}</badge>
                <span v-else>-</span>
            </label-item>
            <label-item icon="ti-bolt" :title="t('global.event')">{{item?.event_text}}</label-item>

            <label-item icon="ti-alert-circle" :title="t('log.level')">
                <badge v-if="item?.level" :theme="item?.level_color">{{item?.level_text}}</badge>
            </label-item>
            <label-item icon="ti-message" :title="t('global.message')">{{item?.message}}</label-item>
            <label-item icon="ti-target" :title="t('global.target')">{{item?.loggable_type_text}} - {{item?.loggable_id}}</label-item>
            <label-item icon="ti-database" :title="t('global.metadata')">{{item?.metadata}}</label-item>
            <label-item icon="ti-world" :title="t('global.ip_address')">{{item?.ip_address}}</label-item>
            <label-item icon="ti-device-desktop" :title="t('global.user_agent')">{{item?.user_agent}}</label-item>
            <label-item icon="ti-eye-check" :title="t('global.is_reviewed')">{{item?.is_reviewed_text}}</label-item>
            <label-item icon="ti-calendar-plus" :title="t('global.created_at')">{{item?.created_at}}</label-item>
            <label-item icon="ti-calendar-event" :title="t('global.updated_at')">{{item?.updated_at}}</label-item>
        </div>
    </card>
</template>

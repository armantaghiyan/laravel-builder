<script setup lang="ts">
import useSettingList from "@/composables/setting/useSettingList.ts";
import useSettingUpdate from "@/composables/setting/useSettingUpdate.ts";
import Setting, {SettingValue} from "@/utils/models/Setting.ts";
import {Permissions} from "@/utils/models/enums.ts";

const {t} = useTranslations();
const {hasPermission} = usePermission();
const {fetchData, items, loading} = useSettingList();
const {update, pending} = useSettingUpdate();

const activeTab = ref('general');
const savingKey = ref<string | null>(null);
const failed = ref(false);
const values = reactive<Record<string, SettingValue>>({});
const errors = reactive<Record<string, string>>({});

const tabs = computed(() => [...new Set(items.value.map(item => item.group))].map(group => ({
    key: group,
    label: t(`settings.groups.${group}`),
})));
const visibleItems = computed(() => items.value.filter(item => item.group === activeTab.value));
const canUpdate = computed(() => !!hasPermission(Permissions.SETTING_UPDATE));

async function load() {
    failed.value = false;

    try {
        await fetchData();
        items.value.forEach(item => values[item.key] = item.value);
    } catch {
        failed.value = true;
    }
}

function setValue(key: string, value: SettingValue) {
    values[key] = value;
    delete errors[key];
}

async function save(item: Setting) {
    if (pending.value || !canUpdate.value) return;

    savingKey.value = item.key;
    delete errors[item.key];
    const value = values[item.key] ?? item.default;

    try {
        await update(item.key, value);
        item.value = value;
        toast(t('settings.saved'));
    } catch (error: any) {
        const messages = error?.response?.data?.errors?.value;
        if (messages?.length) errors[item.key] = messages[messages.length - 1];
    } finally {
        savingKey.value = null;
    }
}

onMounted(load);
</script>

<template>
    <div class="flex flex-col gap-6">
        <h4 class="font-medium text-[24px] pt-2">{{ t('settings.title') }}</h4>

        <p v-if="loading" role="status" class="text-gray-500">{{ t('settings.loading') }}</p>
        <div v-else-if="failed" class="flex items-center gap-4">
            <p class="text-gray-500">{{ t('settings.load_failed') }}</p>
            <app-button outline @click="load">{{ t('access.retry') }}</app-button>
        </div>

        <template v-else>
            <tab-bar v-model="activeTab" :tabs="tabs"/>

            <card :title="t(`settings.groups.${activeTab}`)">
                <div class="px-6 pb-6 flex flex-col gap-6">
                    <form v-for="item in visibleItems" :key="item.key" @submit.prevent="save(item)" class="flex flex-col gap-3">
                        <textarea-input
                            v-if="item.type === 'textarea'"
                            :id="`setting-${item.key}`"
                            :title="t(`settings.fields.${item.key}`)"
                            :model-value="values[item.key] as string"
                            :disabled="!canUpdate || pending"
                            :error="errors[item.key]"
                            :rows="6"
                            @update:model-value="setValue(item.key, $event ?? '')"
                        />
                        <switch-input
                            v-else-if="item.type === 'boolean'"
                            :title="t(`settings.fields.${item.key}`)"
                            :model-value="!!values[item.key]"
                            :disabled="!canUpdate || pending"
                            @update:model-value="setValue(item.key, $event)"
                        />
                        <text-input
                            v-else
                            :id="`setting-${item.key}`"
                            :title="t(`settings.fields.${item.key}`)"
                            :model-value="values[item.key] as string | number"
                            :number-type="item.type === 'integer' ? 'int' : undefined"
                            :disabled="!canUpdate || pending"
                            :error="errors[item.key]"
                            @update:model-value="setValue(item.key, $event ?? '')"
                        />
                        <p v-if="item.key === 'max_sessions'" class="text-sm text-gray-500">{{ t('settings.session_hint') }}</p>
                        <p class="text-sm text-gray-500">{{ t('settings.default_value') }}: {{ item.default === '' ? t('settings.empty') : item.default }}</p>
                        <app-button v-if="canUpdate" type="submit" :loading="savingKey === item.key" :disabled="pending" class="self-start">
                            {{ t('global.submit') }}
                        </app-button>
                    </form>
                </div>
            </card>
        </template>
    </div>
</template>

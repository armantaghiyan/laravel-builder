<script setup lang="ts">
import useLogList from "@/composables/log/useLogList.ts";
import {shortenIP, truncateText} from "@/utils/helper.ts";
import LogReportFragment from '@/components/fragments/log/log-report-fragment.vue';
import LogStatisticsFragment from '@/components/fragments/log/log-statistics-fragment.vue';

const {t} = useTranslations();
const {fetchData, items, count, statistics, params, reFetchData, loading} = useLogList();
const activeTab = ref<'list' | 'report'>('list');


onActivated(() => {
    fetchData();
});
</script>

<template>
    <div class="flex flex-col gap-6">
        <tab-bar
            :tabs="[
                {key: 'list', label: t('menu.list')},
                {key: 'report', label: t('log.report')},
            ]"
            v-model="activeTab"
        />

        <template v-if="activeTab === 'list'">
            <log-statistics-fragment v-if="statistics" :statistics="statistics"/>

            <card :title="t('menu.log')">
            <form @submit.prevent="reFetchData">
                <filter-content>
                    <text-input :title="t('global.id')" v-model="params.id"/>
                    <text-input :title="t('global.user_id')" v-model="params.user_id"/>
                    <select-input :title="t('global.user_guard')" type="log_user_guard" v-model="params.user_guard" with-all/>
                    <select-input :title="t('global.event')" type="log_event" v-model="params.event" with-all/>
                    <select-input :title="t('log.level')" type="log_levels" v-model="params.level" with-all/>
                    <text-input :title="t('global.message')" v-model="params.message"/>
                    <select-input :title="t('global.target')" type="log_loggable_type" v-model="params.loggable_type" with-all/>
                    <text-input :title="t('global.target_id')" v-model="params.loggable_id"/>
                    <text-input :title="t('global.ip_address')" v-model="params.ip_address"/>
                    <select-input :title="t('global.is_reviewed')" type="yes_or_no" v-model="params.is_reviewed" with-all/>
                </filter-content>

                <action-content>
                    <div class="flex gap-4">
                        <text-input :placeholder="t('global.search')" class="sm:w-50 w-full" v-model="params.search"/>
                        <btn-search :loading="loading"/>
                    </div>

                    <div class="flex gap-4">
                        <page-rows v-model="params.page_rows"/>
                    </div>
                </action-content>
            </form>

            <custom-table :loading="loading">
                <custom-thead>
                    <custom-tr>
                        <custom-th fixed :width="120" sort-key="id" v-model:sort="params.sort" v-model:sort-type="params.sort_type">{{ t('global.id') }}</custom-th>
						<custom-th :width="120" sort-key="user_id" v-model:sort="params.sort" v-model:sort-type="params.sort_type">{{ t('global.user_guard') }}</custom-th>
						<custom-th :width="120" sort-key="event" v-model:sort="params.sort" v-model:sort-type="params.sort_type">{{ t('global.event') }}</custom-th>
						<custom-th fixed :width="60">{{ t('log.level') }}</custom-th>
						<custom-th fixed :width="260">{{ t('global.message') }}</custom-th>
						<custom-th :width="120" sort-key="loggable_id" v-model:sort="params.sort" v-model:sort-type="params.sort_type">{{ t('global.target') }}</custom-th>
						<custom-th fixed :width="120">{{ t('global.ip_address') }}</custom-th>
						<custom-th fixed :width="100">{{ t('global.is_reviewed') }}</custom-th>
						<custom-th fixed sort-key="created_at" :width="165" v-model:sort="params.sort" v-model:sort-type="params.sort_type">{{ t('global.created_at') }}</custom-th>

                        <custom-th fixed :width="100">{{ t('global.actions') }}</custom-th>
                    </custom-tr>
                </custom-thead>
                <custom-tbody>
                    <custom-tr v-for="item in items" :key="item.id" class="border-b border-gray-5">
                        <custom-td :copy="item.id">
                            <id-formater :id="item.id"/>
                        </custom-td>
                        <custom-td>
                            <badge v-if="item.user_guard" :theme="item.user_guard_color">{{ item.user_guard_text }} - {{ item.user_id }}</badge>
                            <span v-else>-</span>
                        </custom-td>
                        <custom-td>{{ item.event_text }}</custom-td>
                        <custom-td>
                            <badge :theme="item.level_color">{{ item.level_text }}</badge>
                        </custom-td>
                        <custom-td>{{ truncateText(item.message, 30) }}</custom-td>
                        <custom-td>
                            <span v-if="item.loggable_type">{{ item.loggable_type_text }} - {{ item.loggable_id }}</span>
                            <span v-else>-</span>
                        </custom-td>
                        <custom-td>{{ shortenIP(item.ip_address) }}</custom-td>
                        <custom-td>{{ item.is_reviewed_text }}</custom-td>
                        <custom-td>{{ item.created_at }}</custom-td>

                        <custom-td class="flex">
                            <btn-see :href="`/log/${item.id}`"/>
                        </custom-td>
                    </custom-tr>
                </custom-tbody>
            </custom-table>

            <pagination v-model="params.page" :count="count" :page-rows="params.page_rows" @change="fetchData"/>
        </card>
        </template>

        <log-report-fragment v-else/>
    </div>
</template>

<script setup lang="ts">
import useLogFileList from '@/composables/logFile/useLogFileList.ts';
import useLogFileShow from '@/composables/logFile/useLogFileShow.ts';
import useLogFileDownload from '@/composables/logFile/useLogFileDownload.ts';

const {t, locale} = useTranslations();
const {items, path, failed: listFailed, fetchData, loading: listLoading} = useLogFileList();
const {item, failed: showFailed, show, loading: showLoading} = useLogFileShow();
const {download, failed: downloadFailed, downloadingPath, progress, loading: downloadLoading} = useLogFileDownload();
const search = ref('');
const selectedPath = ref('');
const lines = ref<string | number>(100);
const wrapLines = ref(false);
const preview = ref<HTMLElement>();
const busy = computed(() => listLoading.value || showLoading.value);
const visibleItems = computed(() => items.value.filter(file => file.name.toLowerCase().includes(search.value.toLowerCase())));
const breadcrumbs = computed(() => path.value.split('/').filter(Boolean).map((name, index, parts) => ({
    name,
    path: parts.slice(0, index + 1).join('/'),
})));
const validLines = computed(() => Number.isInteger(Number(lines.value)) && Number(lines.value) >= 1 && Number(lines.value) <= 5000);

function formatSize(bytes: number | null): string {
    if (bytes === null) return '—';
    if (bytes < 1024) return `${bytes} B`;
    if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`;
    if (bytes < 1024 * 1024 * 1024) return `${(bytes / 1024 / 1024).toFixed(1)} MB`;
    return `${(bytes / 1024 / 1024 / 1024).toFixed(1)} GB`;
}

function formatDate(timestamp: number): string {
    return new Date(timestamp * 1000).toLocaleString(locale.value === 'fa' ? 'fa-IR' : 'en-GB');
}

async function openFile(filePath: string) {
    if (busy.value) return;
    if (!validLines.value) lines.value = 100;
    selectedPath.value = filePath;
    await show(filePath, Number(lines.value));
}

async function openDirectory(directory: string, keepSelection = false) {
    if (busy.value) return;
    if (!await fetchData(directory)) return;
    search.value = '';

    if (keepSelection && items.value.some(file => !file.is_directory && file.path === selectedPath.value)) return;

    selectedPath.value = '';
    item.value = null;
    showFailed.value = false;
    const file = items.value.find(file => !file.is_directory && file.name === 'laravel.log')
        ?? items.value.find(file => !file.is_directory);
    if (file) await openFile(file.path);
}

watch(item, async (value) => {
    if (!value) return;
    await nextTick();
    if (preview.value) preview.value.scrollTop = preview.value.scrollHeight;
});

onMounted(() => openDirectory(''));
</script>

<template>
    <div class="flex flex-col gap-6">
        <alert v-if="downloadFailed" variant="danger" :message="t('log_file.download_failed')"/>

        <card :title="t('menu.log_files')">
            <template #header>
                <app-button outline :loading="listLoading" :disabled="busy" @click="openDirectory(path, true)">
                    {{ t('log_file.refresh_files') }}
                </app-button>
            </template>

            <div class="px-6 pb-6 flex flex-col gap-4">
                <nav class="flex flex-wrap items-center gap-2 text-sm" :aria-label="t('log_file.folders')">
                    <button type="button" dir="ltr" class="text-primary hover:underline disabled:opacity-50" :disabled="busy" @click="openDirectory('')">storage/logs</button>
                    <template v-for="crumb in breadcrumbs" :key="crumb.path">
                        <span aria-hidden="true">/</span>
                        <button type="button" dir="ltr" class="text-primary hover:underline disabled:opacity-50" :disabled="busy" @click="openDirectory(crumb.path)">{{ crumb.name }}</button>
                    </template>
                </nav>

                <text-input v-model="search" :placeholder="t('log_file.search_files')" class="sm:max-w-80"/>
                <alert v-if="listFailed" variant="danger" :message="t('log_file.list_failed')"/>
                <p v-if="listLoading" class="text-sm text-gray-500" role="status">{{ t('log_file.loading_files') }}</p>

                <custom-table v-if="visibleItems.length" :loading="listLoading">
                    <custom-thead>
                        <custom-tr>
                            <custom-th>{{ t('global.name') }}</custom-th>
                            <custom-th :width="110">{{ t('log_file.size') }}</custom-th>
                            <custom-th :width="180">{{ t('global.updated_at') }}</custom-th>
                            <custom-th :width="110">{{ t('log_file.download') }}</custom-th>
                        </custom-tr>
                    </custom-thead>
                    <custom-tbody>
                        <custom-tr v-for="file in visibleItems" :key="file.path" :class="{'bg-primary/5': selectedPath === file.path}">
                            <custom-td>
                                <button
                                    type="button"
                                    class="flex items-center gap-2 text-primary hover:underline text-start disabled:opacity-50"
                                    :disabled="busy"
                                    :aria-current="selectedPath === file.path ? 'true' : undefined"
                                    @click="file.is_directory ? openDirectory(file.path) : openFile(file.path)"
                                >
                                    <i :class="file.is_directory ? 'ti ti-folder' : 'ti ti-file-text'" class="text-lg shrink-0" aria-hidden="true"></i>
                                    <span dir="ltr" class="break-all">{{ file.name }}</span>
                                </button>
                            </custom-td>
                            <custom-td><span dir="ltr">{{ formatSize(file.size) }}</span></custom-td>
                            <custom-td>{{ formatDate(file.modified_at) }}</custom-td>
                            <custom-td>
                                <app-button v-if="!file.is_directory" size="sm" outline :disabled="downloadLoading" :loading="downloadingPath === file.path" @click="download(file)">
                                    {{ t('log_file.download') }}
                                </app-button>
                            </custom-td>
                        </custom-tr>
                    </custom-tbody>
                </custom-table>
                <p v-else-if="!listLoading && !listFailed" class="text-sm text-gray-500">{{ search ? t('global.no_results') : t('log_file.empty_folder') }}</p>
            </div>
        </card>

        <card :title="t('log_file.preview')">
            <div class="px-6 pb-6 flex flex-col gap-4">
                <p v-if="!selectedPath" class="text-sm text-gray-500">{{ t('log_file.select_file') }}</p>

                <template v-else>
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div class="min-w-0">
                            <p dir="ltr" class="font-mono text-sm break-all text-start">{{ selectedPath }}</p>
                            <p v-if="item" class="text-xs text-gray-500 mt-2">
                                <span dir="ltr">{{ formatSize(item.size) }}</span>
                                · {{ t('global.updated_at') }}: {{ formatDate(item.modified_at) }}
                            </p>
                        </div>
                        <app-button v-if="item" outline :loading="downloadingPath === item.path" :progress="progress" :disabled="downloadLoading" @click="download(item)">
                            {{ t('log_file.download_full') }}
                        </app-button>
                    </div>

                    <form class="flex flex-wrap items-end gap-3" @submit.prevent="openFile(selectedPath)">
                        <text-input v-model="lines" :title="t('log_file.last_lines')" number-type="int" :min="1" :max="5000" :disabled="busy" class="w-44"/>
                        <app-button type="submit" :loading="showLoading" :disabled="busy || !validLines">{{ t('log_file.show_refresh') }}</app-button>
                        <app-button outline :disabled="showLoading" @click="wrapLines = !wrapLines" :aria-pressed="wrapLines">{{ t('log_file.wrap_lines') }}</app-button>
                    </form>
                    <p class="text-xs text-gray-500">{{ t('log_file.lines_hint') }}</p>
                    <alert v-if="showFailed" variant="danger" :message="t('log_file.show_failed')"/>
                    <p v-if="showLoading" class="text-sm text-gray-500" role="status">{{ t('log_file.loading_content') }}</p>

                    <template v-if="item">
                        <p class="text-sm" role="status">{{ t('log_file.showing_lines', {count: item.line_count, requested: item.requested_lines}) }}</p>
                        <alert v-if="item.truncated" variant="warning" :message="t('log_file.truncated')"/>
                        <p v-if="item.size === 0" class="text-sm text-gray-500">{{ t('log_file.empty_file') }}</p>
                        <pre
                            v-else
                            ref="preview"
                            dir="ltr"
                            tabindex="0"
                            :aria-label="t('log_file.preview')"
                            class="max-h-[65vh] min-h-48 overflow-auto rounded-lg bg-slate-950 p-4 text-start font-mono text-xs leading-6 text-slate-100"
                            :class="wrapLines ? 'whitespace-pre-wrap break-all' : 'whitespace-pre'"
                        >{{ item.content }}</pre>
                    </template>
                </template>
            </div>
        </card>
    </div>
</template>

<script setup>
import {Comment, Fragment, Text, inject, onMounted, onUpdated, ref, useSlots} from 'vue';

const {t} = useTranslations();
const slots = useSlots();
const loading = inject('tableLoading', ref(false));
const tbody = ref(null);
const columns = ref(1);

function hasRows(nodes = slots.default?.() ?? []) {
    return nodes.some(node => {
        if (node.type === Fragment) {
            return hasRows(Array.isArray(node.children) ? node.children : []);
        }

        return node.type !== Comment && (node.type !== Text || String(node.children).trim().length > 0);
    });
}

function updateColumns() {
    const rows = tbody.value?.closest('table')?.tHead?.rows ?? [];
    columns.value = Math.max(1, ...Array.from(rows, row =>
        Array.from(row.cells).reduce((count, cell) => count + cell.colSpan, 0)
    ));
}

onMounted(updateColumns);
onUpdated(updateColumns);
</script>

<template>
    <tbody ref="tbody" class="[&_tr]:border-b [&_tr]:border-gray-100 [&_tr]:transition-colors [&_tr]:duration-150 [&_tr:nth-child(even)]:bg-gray-50/40 [&_tr:hover]:bg-primary/[0.045] [&_tr:last-child]:border-b-0">
    <slot v-if="hasRows()"/>
    <tr v-else-if="!loading" class="bg-transparent! hover:bg-transparent!">
        <td :colspan="columns" class="px-5 py-16">
            <div role="status" aria-live="polite" class="flex flex-col items-center gap-3 text-center">
                <div class="relative flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 ring-8 ring-gray-50">
                    <i class="ti ti-search-off text-3xl text-gray-400" aria-hidden="true"></i>
                </div>
                <div class="space-y-1 pt-2">
                    <p class="text-base font-semibold">{{ t('global.no_results') }}</p>
                </div>
                <slot name="empty-action"/>
            </div>
        </td>
    </tr>
    </tbody>
</template>

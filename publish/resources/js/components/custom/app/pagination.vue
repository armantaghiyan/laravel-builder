<script setup lang="ts">
const props = defineProps<{
    count: number,
    pageRows: number,
    loading?: boolean,
}>();

const emit = defineEmits(['change']);

const page = defineModel<number>({default: 1});
const {t} = useTranslations();
const paginationPageCount = computed(() => Math.max(1, Math.ceil(props.count / Math.max(1, props.pageRows))));
const firstRow = computed(() => props.count === 0 ? 0 : (page.value - 1) * props.pageRows + 1);
const lastRow = computed(() => Math.min(page.value * props.pageRows, props.count));

function changePage(newPage: number|'...') {
    if (newPage === '...' || props.loading) {
        return;
    }

    if (page.value === newPage || newPage < 1 || newPage > paginationPageCount.value) {
        return;
    }

    page.value = newPage;

    emit('change');
}

function getPageList(): (number | "...")[] {
    const pageCount = paginationPageCount.value;
    const currentPage = page.value;

    const pages: (number | "...")[] = [];

    if (pageCount <= 1) return [1];

    if (pageCount <= 5) {
        showRange(1, pageCount, pages);
    } else if (currentPage <= 3) {
        showRange(1, 3, pages);
        pages.push("...");
        pages.push(pageCount);
    } else if (currentPage >= pageCount - 2) {
        pages.push(1);
        pages.push("...");
        showRange(pageCount - 2, pageCount, pages);
    } else {
        pages.push(1);
        pages.push("...");
        pages.push(currentPage);
        pages.push("...");
        pages.push(pageCount);
    }

    return pages;
}

const showRange = (start: number, end: number, pages: (number | "...")[]) => {
    for (let pageNumber = start; pageNumber <= end; pageNumber++) {
        if (pageNumber > 0 && pageNumber <= paginationPageCount.value) {
            pages.push(pageNumber);
        }
    }
};

watch(() => [props.count, props.pageRows, props.loading], () => {
    if (!props.loading && page.value > paginationPageCount.value) {
        changePage(paginationPageCount.value);
    }
});
</script>

<template>
    <nav :aria-label="t('ux.pagination')" class="flex flex-wrap justify-between items-center gap-3 p-4">
        <div class="text-sm text-gray-600 tabular-nums" role="status" aria-live="polite">
            {{
                t('pagination.desc', {
                    p1: firstRow,
                    p2: lastRow,
                    p3: count,
                })
            }}
        </div>

        <div v-if="paginationPageCount > 1" dir="ltr" class="flex gap-1 sm:gap-1.5">
            <btn-pagination :is-active="false" :disabled="loading || page <= 1" :aria-label="t('ux.first_page')" @click="changePage(1)" class="sm:flex hidden">
                <i class="ti ti-chevrons-left" aria-hidden="true"></i>
            </btn-pagination>
            <btn-pagination :is-active="false" :disabled="loading || page <= 1" :aria-label="t('ux.previous_page')" @click="changePage(page - 1)">
                <i class="ti ti-chevron-left" aria-hidden="true"></i>
            </btn-pagination>

            <btn-pagination
                v-for="(pageNumber, index) in getPageList()"
                :key="index"
                :is-active="pageNumber === page"
                :aria-label="pageNumber === '...' ? undefined : t('ux.page', {page: pageNumber})"
                @click="changePage(pageNumber)"
                :disabled="loading || pageNumber === '...'"
            >
                {{ pageNumber }}
            </btn-pagination>

            <btn-pagination :is-active="false" :disabled="loading || page >= paginationPageCount" :aria-label="t('ux.next_page')" @click="changePage(page + 1)">
                <i class="ti ti-chevron-right" aria-hidden="true"></i>
            </btn-pagination>
            <btn-pagination :is-active="false" :disabled="loading || page >= paginationPageCount" :aria-label="t('ux.last_page')" @click="changePage(paginationPageCount)" class="sm:flex hidden">
                <i class="ti ti-chevrons-right" aria-hidden="true"></i>
            </btn-pagination>
        </div>
    </nav>
</template>

<script setup lang="ts">
const props = defineProps<{
    count: number,
    pageRows: number,
}>();

const emit = defineEmits(['change']);

const page = defineModel<number>();
const {t} = useTranslations();
const paginationPageCount = ref(1);

function changePage(newPage: number|'...') {
    if (newPage === '...') {
        return;
    }

    if (page.value === newPage) {
        return;
    }

    page.value = newPage;

    emit('change');
}

function getPageList(): (number | "...")[] {
    const pageCount = paginationPageCount.value;
    const currentPage = page.value as number;

    const pages: (number | "...")[] = [];

    if (pageCount <= 1) return [1];

    if (pageCount <= 5) {
        showRange(1, pageCount, pages);
    } else if (currentPage <= 4) {
        showRange(1, 5, pages);
        pages.push("...");
        pages.push(pageCount);
    } else if (currentPage >= pageCount - 3) {
        pages.push(1);
        pages.push("...");
        showRange(pageCount - 4, pageCount, pages);
    } else {
        pages.push(1);
        pages.push("...");
        showRange(currentPage - 1, currentPage + 1, pages);
        pages.push("...");
        pages.push(pageCount);
    }

    return pages;
}

const showRange = (start: number, end: number, pages: (number | "...")[]) => {
    for (let i = start; i <= end; i++) {
        if (i > 0 && i <= paginationPageCount.value) {
            pages.push(i);
        }
    }
};

function calcPaginationPageCount(){
    paginationPageCount.value = Math.ceil(props.count / props.pageRows);
}

watch(() => props.pageRows, () => {
    calcPaginationPageCount();
});

watch(() => props.count, () => {
    calcPaginationPageCount();
});

onMounted(() => {
    calcPaginationPageCount();
});
</script>

<template>
    <div  class="flex md:justify-between justify-center items-center p-4">
        <div class="text-gray-400 md:flex hidden">
            {{
                t('pagination.desc', {
                    p1: pageRows * (page as number) - (pageRows - 1),
                    p2: pageRows * (page as number),
                    p3: count,
                })
            }}
        </div>

        <div v-if="paginationPageCount > 1" class="flex gap-1.5" :class="{
            'flex-row-reverse' : true,
        }">
            <btn-pagination :is-active="false" :disabled="page === 1" @click="changePage(1)" class="sm:flex hidden">
                <i class="ti ti-chevrons-left"></i>
            </btn-pagination>
            <btn-pagination :is-active="false" :disabled="page === 1" @click="changePage((page as number) - 1)">
                <i class="ti ti-chevron-left"></i>
            </btn-pagination>

            <btn-pagination
                v-for="i in getPageList()"
                :is-active="i == page"
                @click="changePage(i)"
                :disabled="i === '...'"
            >
                {{ i }}
            </btn-pagination>


            <btn-pagination :is-active="false" :disabled="page === paginationPageCount" @click="changePage((page as number) + 1)">
                <i class="ti ti-chevron-right"></i>
            </btn-pagination>
            <btn-pagination :is-active="false" :disabled="page === paginationPageCount" @click="changePage(paginationPageCount)" class="sm:flex hidden">
                <i class="ti ti-chevrons-right"></i>
            </btn-pagination>
        </div>
    </div>
</template>

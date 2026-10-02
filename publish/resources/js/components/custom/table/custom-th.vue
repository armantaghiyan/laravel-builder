<script setup lang="ts">
import type {Ref} from 'vue';

const loading = inject<Ref<boolean>>('tableLoading', ref(false));
const props = defineProps<{
    width?: number
    fixed?: boolean
    sortKey?: string | null
    sort?: string | null
    sortType?: string | null
}>()

const emit = defineEmits<{
    (e: 'update:sort', value: string | null): void
    (e: 'update:sortType', value: string | null): void
}>()

const sort = computed<string | null>({
    get: () => props.sort ?? null,
    set: v => emit('update:sort', v),
})

const sortType = computed<string | null>({
    get: () => props.sortType ?? null,
    set: v => emit('update:sortType', v),
})

function getFixWidth() {
    if (!props.fixed) return 'auto'
    return props.width ? `${props.width}px` : 'auto'
}

function getWidth() {
    return props.width ? `${props.width}px` : 'auto'
}

function changeSort() {
    if (!props.sortKey) return

    let nextType = 'desc';
    if (props.sortKey === sort.value) {
        nextType = sortType.value === 'asc' ? 'desc' : 'asc'
    }

    sort.value = props.sortKey
    sortType.value = nextType
}
</script>

<template>
    <th
        scope="col"
        :aria-sort="sortKey ? (sort !== sortKey ? 'none' : sortType === 'asc' ? 'ascending' : 'descending') : undefined"
        class="relative font-bold px-5 py-4 text-start text-[12px] text-gray-700 bg-gray-50/80 uppercase tracking-[0.035em] first:rounded-ss-xl last:rounded-et-xl transition-colors hover:bg-gray-100 duration-300"
        :class="{ 'cursor-pointer': !!sortKey }"
        :style="`width: ${getFixWidth()}; min-width: ${getWidth()}; max-width: ${getWidth()};`"
    >
        <button v-if="sortKey" type="button" :disabled="loading" class="flex w-full items-center gap-2 text-start cursor-pointer disabled:cursor-wait" @click="changeSort">
            <span class="flex-1"><slot/></span>
            <i class="ti shrink-0" :class="sort !== sortKey ? 'ti-arrows-sort text-gray-600' : sortType === 'asc' ? 'ti-sort-ascending text-primary' : 'ti-sort-descending text-primary'" aria-hidden="true"></i>
        </button>
        <slot v-else/>
    </th>
</template>

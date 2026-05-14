<script setup lang="ts">
import {computed} from 'vue'

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
        @click="changeSort"
        class="relative font-medium px-5 py-4 text-start text-[13px]"
        :class="{ 'cursor-pointer': !!sortKey }"
        :style="`width: ${getFixWidth()}; min-width: ${getWidth()}; max-width: ${getWidth()};`"
    >
        <slot/>

        <div
            v-if="sortKey"
            class="absolute top-0 bottom-0 end-0 w-6 flex items-center justify-center"
        >
            <div class="rotate-90">
                <i class="ti ti-chevron-left duration-200" :class="{'text-gray-2': sort !== sortKey || sortType !== 'desc'}"></i>
                <i class="ti ti-chevron-right duration-200" :class="{'text-gray-2': sort !== sortKey || sortType !== 'asc'}"></i>
            </div>
        </div>
    </th>
</template>

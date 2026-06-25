<template>
    <div ref="wrapperRef">
        <label v-if="title" :for="inputId" class="text-[13px]  block">
            {{ title }}
            <span v-if="required" class="text-red-500 ml-0.5">*</span>
        </label>

        <div
            class="relative w-full rounded-lg border border-gray-2 hover:border-gray-3 focus:border-2 focus:border-primary bg-white transition-all duration-200"
            :class="{
                'ring-2 ring-primary/30 border-primary': isOpen,
                'border-red-400! ring-2 ring-red-100': error,
                'opacity-50 cursor-not-allowed pointer-events-none': disabled,
            }"
        >
            <!-- Inner: wrap tags + input -->
            <div
                class="flex flex-wrap items-center gap-1.5 px-3 py-2 max-h-9 cursor-text"
                @click="openDropdown"
            >
                <!-- تمام تگ‌ها — بدون هیچ محدودیتی -->
                <template v-if="multiple && Array.isArray(modelValue)">
                    <TransitionGroup name="tag">
                        <span
                            v-for="(item, i) in modelValue"
                            :key="trackBy ? item[trackBy] : item"
                            class="inline-flex items-center gap-1 bg-primary/10 text-primary text-[12px] font-medium rounded-full pl-2.5 pr-1.5 py-1 border border-primary/20 hover:border-primary/40 transition-colors"
                        >
                            <span class="max-w-35 truncate">{{ getLabel(item) }}</span>
                            <button
                                type="button"
                                tabindex="-1"
                                class="w-3.5 h-3.5 rounded-full bg-primary/15 hover:bg-red-100 hover:text-red-500 text-primary/60 transition-all flex items-center justify-center shrink-0"
                                :disabled="disabled"
                                @click.stop="removeItem(i)"
                            >
                                <svg class="w-2 h-2" viewBox="0 0 8 8" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M1 1l6 6M7 1L1 7"/>
                                </svg>
                            </button>
                        </span>
                    </TransitionGroup>
                </template>

                <!-- Single -->
                <span v-else-if="!multiple && modelValue" class="text-sm text-gray-700 truncate flex-1">
                    {{ getLabel(modelValue) }}
                </span>

                <!-- Search input -->
                <input
                    v-if="searchable"
                    ref="searchRef"
                    v-model="searchQuery"
                    type="text"
                    class="auto-placeholder flex-1 min-w-25 outline-none bg-transparent text-sm text-gray-700 placeholder:text-gray-400 placeholder:text-[13px] py-0.5"
                    :placeholder="hasSelection ? '' : placeholder"
                    :disabled="disabled"
                    @focus="openDropdown"
                    @blur="onInputBlur"
                    @keydown="onKeydown"
                    @input="onSearchInput"
                />
                <span v-else-if="!hasSelection" class="text-gray-400 text-[13px] flex-1 select-none">
                    {{ placeholder }}
                </span>

                <!-- Clear + chevron — همیشه آخر ردیف -->
                <div class="flex items-center gap-1 ml-auto pl-1 shrink-0 self-center">
                    <button
                        v-if="clearable && hasSelection"
                        type="button"
                        tabindex="-1"
                        class="w-5 h-5 rounded-full hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-all flex items-center justify-center"
                        @click.stop="clearAll"
                    >
                        <svg class="w-3 h-3" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M2 2l8 8M10 2l-8 8"/>
                        </svg>
                    </button>
                    <div
                        class="w-6 h-6 rounded-full flex items-center justify-center transition-all"
                        :class="isOpen ? 'bg-primary/10' : 'hover:bg-gray-100'"
                    >
                        <svg
                            class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200"
                            :class="{ 'rotate-180 text-primary': isOpen }"
                            viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2"
                        >
                            <path d="M3 5l4 4 4-4"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Dropdown -->
            <Transition
                enter-active-class="transition-all duration-150 ease-out"
                enter-from-class="opacity-0 -translate-y-2 scale-95"
                enter-to-class="opacity-100 translate-y-0 scale-100"
                leave-active-class="transition-all duration-100 ease-in"
                leave-from-class="opacity-100 translate-y-0 scale-100"
                leave-to-class="opacity-0 -translate-y-2 scale-95"
            >
                <div
                    v-if="isOpen"
                    class="absolute z-50 left-0 right-0 mt-1.5 bg-white border border-gray-100 rounded-xl shadow-xl shadow-gray-200/60 overflow-hidden"
                    :style="{ top: '100%' }"
                >
                    <div v-if="isLoading" class="flex items-center gap-2.5 px-4 py-3.5 text-sm text-gray-400">
                        <svg class="w-4 h-4 animate-spin text-primary" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                        </svg>
                        {{ loadingText }}
                    </div>

                    <ul
                        v-else-if="filteredOptions.length"
                        class="overflow-y-auto py-1.5"
                        :style="{ maxHeight: `${maxHeight}px` }"
                        role="listbox"
                    >
                        <li
                            v-if="multiple && showSelectAll && filteredOptions.length > 1"
                            class="flex items-center gap-3 mx-1.5 px-3 py-2 text-[13px] text-gray-500 hover:bg-gray-50 rounded-lg cursor-pointer border-b border-gray-100 mb-1 select-none"
                            @mousedown.prevent="toggleAll"
                        >
                            <span
                                class="w-4 h-4 rounded border-2 flex items-center justify-center shrink-0 transition-all"
                                :class="allSelected ? 'bg-primary border-primary' : someSelected ? 'bg-primary/10 border-primary/50' : 'border-gray-300'"
                            >
                                <svg v-if="allSelected" class="w-2.5 h-2.5 text-white" viewBox="0 0 10 10" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1.5 5l2.5 2.5 4.5-4"/></svg>
                                <svg v-else-if="someSelected" class="w-2 h-2 text-primary" viewBox="0 0 8 8" fill="currentColor"><rect x="1" y="3" width="6" height="2" rx="1"/></svg>
                            </span>
                            <span class="font-medium">انتخاب همه</span>
                        </li>

                        <li
                            v-for="(option, idx) in filteredOptions"
                            :key="trackBy ? option[trackBy] : option"
                            class="flex items-center gap-3 mx-1.5 px-3 py-2.5 text-sm cursor-pointer select-none rounded-lg transition-all duration-100"
                            :class="[
                                isSelected(option) ? 'bg-primary/8 text-primary' : 'text-gray-700 hover:bg-gray-50',
                                idx === highlightedIndex ? 'bg-gray-100!' : '',
                            ]"
                            role="option"
                            :aria-selected="isSelected(option)"
                            @mousedown.prevent="selectOption(option)"
                        >
                            <span
                                v-if="multiple"
                                class="w-4 h-4 rounded border-2 flex items-center justify-center shrink-0 transition-all"
                                :class="isSelected(option) ? 'bg-primary border-primary' : 'border-gray-300'"
                            >
                                <svg v-if="isSelected(option)" class="w-2.5 h-2.5 text-white" viewBox="0 0 10 10" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1.5 5l2.5 2.5 4.5-4"/></svg>
                            </span>

                            <slot name="option" :option="option" :selected="isSelected(option)">
                                <span class="truncate flex-1">{{ getLabel(option) }}</span>
                            </slot>

                            <svg
                                v-if="!multiple && isSelected(option)"
                                class="w-4 h-4 text-primary ml-auto shrink-0"
                                viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2.5"
                            >
                                <path d="M2 7l3.5 3.5L12 3"/>
                            </svg>
                        </li>
                    </ul>

                    <div v-else class="px-4 py-6 text-sm text-gray-400 text-center">
                        <svg class="w-8 h-8 mx-auto mb-2 text-gray-200" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.5">
                            <circle cx="16" cy="16" r="12"/><path d="M12 16h8M16 12v8" opacity="0.5"/>
                        </svg>
                        <slot name="noResult">{{ searchQuery ? noResultText : noOptionsText }}</slot>
                    </div>

                    <div
                        v-if="taggable && searchQuery && !exactMatch"
                        class="border-t border-gray-100 mx-1.5 mt-1 px-3 py-2.5 flex items-center gap-2 text-[13px] text-primary hover:bg-primary/5 cursor-pointer rounded-lg mb-1 transition-colors"
                        @mousedown.prevent="addTag"
                    >
                        <svg class="w-3.5 h-3.5" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 1v12M1 7h12"/></svg>
                        <span>افزودن <strong>«{{ searchQuery }}»</strong></span>
                    </div>
                </div>
            </Transition>
        </div>

        <p v-if="error" class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
            <svg class="w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                <path d="M6 1a5 5 0 100 10A5 5 0 006 1zm0 3a.5.5 0 01.5.5v2a.5.5 0 01-1 0v-2A.5.5 0 016 4zm0 5a.75.75 0 110-1.5.75.75 0 010 1.5z"/>
            </svg>
            {{ error }}
        </p>
        <p v-if="hint && !error" class="text-gray-400 text-xs mt-1.5">{{ hint }}</p>
    </div>
</template>

<style>
.auto-placeholder::placeholder {
    text-align: start;
    direction: auto;
    unicode-bidi: plaintext;
}
/* Tag enter/leave animations */
.tag-enter-active { transition: all 0.15s ease-out; }
.tag-leave-active { transition: all 0.1s ease-in; }
.tag-enter-from  { opacity: 0; transform: scale(0.8); }
.tag-leave-to    { opacity: 0; transform: scale(0.8); }
</style>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount, nextTick, watch } from 'vue'

const props = defineProps({
    modelValue:    { default: null },
    options:       { type: Array,   default: () => [] },
    multiple:      { type: Boolean, default: false },
    searchable:    { type: Boolean, default: true },
    disabled:      { type: Boolean, default: false },
    required:      { type: Boolean, default: false },
    clearable:     { type: Boolean, default: true },
    taggable:      { type: Boolean, default: false },
    showSelectAll: { type: Boolean, default: true },
    title:         { type: String,  default: '' },
    placeholder:   { type: String,  default: 'انتخاب کنید...' },
    hint:          { type: String,  default: '' },
    error:         { type: String,  default: '' },
    optionLabel:   { type: String,  default: '' },
    trackBy:       { type: String,  default: '' },
    maxHeight:     { type: Number,  default: 280 },
    fetchUrl:      { type: String,  default: '' },
    fetchParams:   { type: Object,  default: () => ({}) },
    fetchDebounce: { type: Number,  default: 300 },
    responseKey:   { type: String,  default: '' },
    fetchOnMount:  { type: Boolean, default: false },
    noOptionsText: { type: String,  default: 'موردی برای نمایش وجود ندارد' },
    noResultText:  { type: String,  default: 'نتیجه‌ای یافت نشد' },
    loadingText:   { type: String,  default: 'در حال بارگذاری...' },
})

const emit = defineEmits([
    'update:modelValue', 'select', 'remove',
    'search-change', 'open', 'close', 'tag', 'fetch-error',
])

const inputId = `msi-${Math.random().toString(36).slice(2, 7)}`
const wrapperRef       = ref(null)
const searchRef        = ref(null)
const isOpen           = ref(false)
const searchQuery      = ref('')
const isLoading        = ref(false)
const fetchedOptions   = ref([])
const highlightedIndex = ref(-1)
let debounceTimer = null

const sourceOptions = computed(() => props.fetchUrl ? fetchedOptions.value : props.options)

function getLabel(item) {
    if (!item) return ''
    if (props.optionLabel && typeof item === 'object') return item[props.optionLabel] ?? ''
    return String(item)
}
function getKey(item) {
    if (props.trackBy && typeof item === 'object') return item[props.trackBy]
    return item
}
const filteredOptions = computed(() => {
    if (!searchQuery.value || props.fetchUrl) return sourceOptions.value
    const q = searchQuery.value.toLowerCase()
    return sourceOptions.value.filter(o => getLabel(o).toLowerCase().includes(q))
})
const exactMatch = computed(() =>
    sourceOptions.value.some(o => getLabel(o).toLowerCase() === searchQuery.value.toLowerCase())
)
const hasSelection = computed(() =>
    props.multiple
        ? Array.isArray(props.modelValue) && props.modelValue.length > 0
        : props.modelValue !== null && props.modelValue !== undefined && props.modelValue !== ''
)
function isSelected(option) {
    if (props.multiple) {
        if (!Array.isArray(props.modelValue)) return false
        return props.modelValue.some(v => getKey(v) === getKey(option))
    }
    return props.modelValue !== null && getKey(props.modelValue) === getKey(option)
}
const allSelected = computed(() =>
    props.multiple &&
    Array.isArray(props.modelValue) &&
    filteredOptions.value.length > 0 &&
    filteredOptions.value.every(o => isSelected(o))
)
const someSelected = computed(() =>
    props.multiple &&
    Array.isArray(props.modelValue) &&
    filteredOptions.value.some(o => isSelected(o)) &&
    !allSelected.value
)

function openDropdown() {
    if (props.disabled || isOpen.value) return
    isOpen.value = true
    highlightedIndex.value = -1
    emit('open')
    nextTick(() => searchRef.value?.focus())
}
function closeDropdown() {
    isOpen.value = false
    searchQuery.value = ''
    highlightedIndex.value = -1
    emit('close')
}
function onInputBlur() {
    setTimeout(() => {
        if (!wrapperRef.value?.contains(document.activeElement)) closeDropdown()
    }, 150)
}
function onClickOutside(e) {
    if (!wrapperRef.value?.contains(e.target)) closeDropdown()
}
onMounted(() => document.addEventListener('mousedown', onClickOutside))
onBeforeUnmount(() => document.removeEventListener('mousedown', onClickOutside))

function selectOption(option) {
    if (props.multiple) {
        const current = Array.isArray(props.modelValue) ? [...props.modelValue] : []
        const idx = current.findIndex(v => getKey(v) === getKey(option))
        if (idx === -1) { current.push(option); emit('select', option) }
        else            { current.splice(idx, 1); emit('remove', option) }
        emit('update:modelValue', current)
    } else {
        emit('update:modelValue', isSelected(option) ? null : option)
        emit('select', option)
        closeDropdown()
    }
    searchQuery.value = ''
}
function removeItem(index) {
    const current = Array.isArray(props.modelValue) ? [...props.modelValue] : []
    const [removed] = current.splice(index, 1)
    emit('remove', removed)
    emit('update:modelValue', current)
}
function clearAll() {
    emit('update:modelValue', props.multiple ? [] : null)
}
function toggleAll() {
    if (allSelected.value) {
        const remaining = (props.modelValue || []).filter(
            v => !filteredOptions.value.some(o => getKey(o) === getKey(v))
        )
        emit('update:modelValue', remaining)
    } else {
        const current = Array.isArray(props.modelValue) ? [...props.modelValue] : []
        filteredOptions.value.forEach(o => {
            if (!current.some(v => getKey(v) === getKey(o))) current.push(o)
        })
        emit('update:modelValue', current)
    }
}
function onKeydown(e) {
    if (!isOpen.value && (e.key === 'ArrowDown' || e.key === 'Enter')) { openDropdown(); return }
    if (e.key === 'Escape') { closeDropdown(); return }
    if (e.key === 'ArrowDown') { e.preventDefault(); highlightedIndex.value = Math.min(highlightedIndex.value + 1, filteredOptions.value.length - 1) }
    if (e.key === 'ArrowUp')   { e.preventDefault(); highlightedIndex.value = Math.max(highlightedIndex.value - 1, 0) }
    if (e.key === 'Enter' && highlightedIndex.value >= 0) selectOption(filteredOptions.value[highlightedIndex.value])
    if (e.key === 'Backspace' && !searchQuery.value && props.multiple) {
        const current = Array.isArray(props.modelValue) ? [...props.modelValue] : []
        if (current.length) { current.pop(); emit('update:modelValue', current) }
    }
}
function onSearchInput() {
    emit('search-change', searchQuery.value)
    if (!props.fetchUrl) return
    clearTimeout(debounceTimer)
    debounceTimer = setTimeout(() => fetchOptions(searchQuery.value), props.fetchDebounce)
}
async function fetchOptions(query = '') {
    if (!props.fetchUrl) return
    isLoading.value = true
    try {
        const {callApi} = useCallApi();
        const res = await callApi(props.fetchUrl)
        fetchedOptions.value = res.data.data.items;
    } catch (err) {
        emit('fetch-error', err)
        fetchedOptions.value = []
    } finally {
        isLoading.value = false
    }
}
function addTag() {
    if (!searchQuery.value) return
    const tag = searchQuery.value.trim()
    if (!props.fetchUrl) fetchedOptions.value.push(tag)
    emit('tag', tag)
    selectOption(tag)
}

onMounted(() => { if (props.fetchUrl && props.fetchOnMount) fetchOptions() })
watch(() => props.fetchUrl, url => { if (url) fetchOptions() })

defineExpose({ fetchOptions, clearAll, open: openDropdown, close: closeDropdown })
</script>

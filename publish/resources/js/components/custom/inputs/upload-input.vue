<template>
    <div class="w-full">
        <div class="w-full cursor-pointer">
            <label v-if="title" class="pb-1 text-[13px]">{{ title }}</label>
            <div class="w-full">
                <input
                    @change="onchange"
                    class="fileSelector custom-file-input hidden"
                    ref="fileInput"
                    :name="name"
                    type="file"
                    :required="required !== undefined"
                    :accept="accept"
                    :multiple="multiple"
                />
                <div :title="name" @click="fileInput.click()" class="w-full">
                    <app-button v-if="fileSelected" class="flex-center-center text-dark w-full">
                        {{ truncateText(name, 20) }}
                    </app-button>
                    <app-button v-else class="w-full">
                        <div v-if="placeholder">{{ placeholder }}</div>
                        <div v-else>انتخاب فایل</div>
                    </app-button>
                </div>
            </div>
        </div>

        <div v-if="file.length !== 0 || oldFiles.length !== 0" class="flex flex-wrap pt-1" style="gap: 8px;">
            <div v-for="(item, index) in oldFiles" :key="'old-' + index" class="flex items-center py-1 px-2 rounded-[10px] bg-gray-2">
                <a target="_blank" :href="item.file">فایل {{ index + 1 }}</a>
                <div @click="$emit('onRemove', item)" class="p-1.5 cursor-pointer">
                    <i class="ti ti-x text-danger"></i>
                </div>
            </div>

            <div v-for="(item, index) in file" :key="'new-' + index" class="flex items-center py-1 px-2 rounded-[10px] bg-gray-2">
                <a target="_blank" :href="getImageHref(item)">فایل {{ oldFiles.length + index + 1 }}</a>
                <div @click="removeFile(index)" class="p-1.5 cursor-pointer">
                    <i class="ti ti-x text-danger"></i>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import imageCompression from 'browser-image-compression'
import {useAlert} from "@/composables/useAlert.js";

// Props
const props = defineProps({
    title: String,
    placeholder: String,
    required: String,
    multiple: Boolean,
    accept: String,
    val: { default: null, type: String },
    removeKey: { type: Array, default: '' }
})

const emit = defineEmits(['update:modelValue', 'onRemove'])
const alert = useAlert();

const fileInput = ref(null)
const file = ref([])
const name = ref('')
const fileSelected = ref(false)
const oldFiles = ref([])

watch(() => props.val, () => {
    setValues()
})

onMounted(() => {
    setValues()
})

function setValues() {
    if (props.val) {
        oldFiles.value = props.val
    }
}

function truncateText(text, maxLength = 50) {
    if (text.length <= maxLength) return text
    return text.slice(0, maxLength) + '...'
}

function getImageHref(fileItem) {
    return URL.createObjectURL(fileItem)
}

function setName() {
    fileSelected.value = file.value.length > 0
    name.value = file.value.map(f => f.name).join(', ')
}

async function onchange(event) {
    file.value = Array.from(event.target.files)

    for (let i = 0; i < file.value.length; i++) {
        if (file.value[i].type.startsWith('image/')) {
            file.value[i] = await compress(file.value[i])
        }
    }

    setName()
    emitFiles()
}

function emitFiles() {
    if (props.multiple) {
        emit('update:modelValue', file.value)
    } else {
        emit('update:modelValue', file.value[0] || '')
    }
}

async function removeFile(index) {
    if(await alert.confirm()){
        file.value.splice(index, 1)
        setName()
        emitFiles()
    }
}

async function compress(fileItem) {
    try {
        const compressed = await imageCompression(fileItem, {
            useWebWorker: true
        })
        return new File([compressed], fileItem.name, {
            type: compressed.type,
            lastModified: Date.now()
        })
    } catch (e) {
        console.error('خطا در فشرده‌سازی:', e)
        return fileItem
    }
}
</script>

<style>
.btn-selected-file {
    background-color: #99d9b4 !important;
}
</style>

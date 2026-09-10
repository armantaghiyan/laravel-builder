<script setup lang="ts">
import {Switch} from "@headlessui/vue";

const emit = defineEmits(['onClick']);
const model = defineModel<boolean>();

const {disabled = false} = defineProps<{
    href?: string,
    title?: string,
    disabled?: boolean,
}>();
</script>

<template>
    <div class="flex items-center">
        <div v-if="!href" class="grow text-gray-700">{{ title }}</div>
        <router-link v-else :to="href" class="grow text-primary">{{ title }}</router-link>

        <div @click="emit('onClick')">
            <Switch
                :disabled="disabled"
                v-model="model"
                :class="model ? 'bg-primary' : 'bg-gray-300'"
                class="relative  inline-flex h-6 w-12.5 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
            >
                <span
                    aria-hidden="true"
                    :class="model ? 'inset-s-6.5' : 'inset-s-0'"
                    class="relative duration-200 pointer-events-none inline-block size-5 transform rounded-full bg-white shadow-lg ring-0 transition ease-in-out"
                />
            </Switch>
        </div>
    </div>
</template>

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
        <div v-if="!href" class="grow text-gray-9">{{ title }}</div>
        <router-link v-else :to="href" class="grow text-primary">{{ title }}</router-link>

        <div @click="emit('onClick')">
            <Switch
                :disabled="disabled"
                v-model="model"
                :class="model ? 'bg-primary' : 'bg-gray-2'"
                class="relative inline-flex h-[24px] w-[50px] shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
            >
                <span
                    aria-hidden="true"
                    :class="model ? 'start-6.5' : 'start-0'"
                    class="relative duration-200 pointer-events-none inline-block size-[20px] transform rounded-full bg-white shadow-lg ring-0 transition ease-in-out"
                />
            </Switch>
        </div>
    </div>
</template>

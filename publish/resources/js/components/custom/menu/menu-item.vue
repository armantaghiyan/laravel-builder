<script setup lang="ts">
defineProps<{
    title: string;
    href: string;
}>();
</script>

<template>
    <router-link
        :to="href"
        active-class="menu-active-item"
        class="menu-item-link group relative flex items-center gap-2.5 w-full h-10 px-3 mt-1.5 rounded-lg
               text-slate-300 transition-all duration-300
               hover:bg-white/10 hover:text-white
               active:scale-[0.98]"
    >
        <span
            class="indicator absolute right-0 top-1/2 -translate-y-1/2 h-5 w-1 rounded-full bg-primary
                   scale-y-0 opacity-0 transition-all duration-300"
        ></span>

        <span class="flex items-center justify-center shrink-0 transition-transform duration-300 group-hover:scale-110 text-current">
            <slot />
        </span>

        <span class="text-[14.5px] font-medium leading-none truncate text-current">
            {{ title }}
        </span>
    </router-link>
</template>

<style scoped>
.menu-item-link {
    color: #a3a9c2;
}

.menu-item-link:hover {
    color: #ffffff;
    background-color: rgba(255, 255, 255, 0.08);
}

.menu-active-item {
    @apply bg-primary font-semibold text-white;
}

.menu-active-item .indicator {
    transform: translateY(-50%) scaleY(1);
    opacity: 1;
}
</style>

<script setup lang="ts">
import {Permissions} from "@/utils/models/enums.ts";
import CustomScroll from "@/components/custom/app/custom-scroll.vue";

const {hasPermission} = usePermission();
const isXl = useBreakpoint('xl');
const $app = appStore()
const {t} = useTranslations();
const route = useRoute();


watch(() => route.path, () => {
    if(!isXl.value) {
        $app.isOpenSidebar = false;
    }
});

watch(isXl, () => {
    $app.isOpenSidebar = isXl.value;
});


onMounted(() => {
    $app.isOpenSidebar = isXl.value;
    $app.loadContentMaxWidth();
});


interface MenuChild {
    href: string;
    titleKey: string;
    icon: string;
    permission?: Permissions;
}

interface MenuLeaf {
    type: 'item';
    href: string;
    titleKey: string;
    icon: string;
    permission?: Permissions;
}

interface MenuGroupEntry {
    type: 'group';
    titleKey: string;
    icon: string;
    children: MenuChild[];
}

type MenuEntry = MenuLeaf | MenuGroupEntry;

const menuConfig: MenuEntry[] = [
    {
        type: 'item',
        href: '/',
        titleKey: 'menu.dashboard',
        icon: 'ti ti-smart-home',
    },
    {
        type: 'item',
        href: '/transaction',
        titleKey: 'menu.transactions',
        icon: 'ti ti-credit-card-pay',
        permission: Permissions.TRANSACTION_INDEX,
    },
    {
        type: 'item',
        href: '/category',
        titleKey: 'menu.categories',
        icon: 'ti ti-category-2',
        permission: Permissions.CATEGORY_INDEX,
    },
    {
        type: 'group',
        titleKey: 'menu.settings',
        icon: 'ti ti-settings-cog',
        children: [
            {
                href: '/admin',
                titleKey: 'menu.admin',
                icon: 'ti ti-user',
                permission: Permissions.ADMIN_INDEX,
            },
            {
                href: '/access',
                titleKey: 'menu.access',
                icon: 'ti ti-fingerprint',
                permission: Permissions.ROLE_INDEX,
            },
        ],
    },
];


function canSeeChild(child: { permission?: any }) {
    return !child.permission || hasPermission(child.permission);
}

function isGroupVisible(entry: (typeof menuConfig)[number]) {
    if (entry.type !== 'group') return true;
    return entry.children.some(canSeeChild);
}

const visibleMenu = computed(() =>
    menuConfig.filter((entry) => {
        if (entry.type === 'item') return canSeeChild(entry);
        return isGroupVisible(entry);
    })
);
</script>


<template>
    <div class="relative z-100">
        <fade-animate :duration="200">
            <div v-if="$app.isOpenSidebar && !isXl" @click="$app.isOpenSidebar = false" class="bg-gray-500/50 w-full h-screen fixed to-pink-50 right-0 left-0 z-40"></div>
        </fade-animate>

        <custom-scroll class="fixed sidebar-surface text-menu-color w-65 duration-200 h-full z-40 shadow-[0_0_35px_rgba(26,29,47,0.20)]" :class="{'inset-s-0': $app.isOpenSidebar, '-inset-s-65': !$app.isOpenSidebar}">
            <div>
                <button
                    type="button"
                    class="absolute xl:block hidden top-3 left-3 size-8 rounded-lg text-white/70 hover:bg-white/10 hover:text-white"
                    aria-label="Toggle content width"
                    @click="$app.toggleContentMaxWidth()"
                >
                    <i class="tf-icons text-[20px]" :class="$app.isContentMaxWidth ? 'ti ti-arrows-maximize' : 'ti ti-arrows-minimize'"></i>
                </button>
                <div class="h-22 flex items-center gap-3 px-5 pl-14">
                    <div class="size-10 rounded-xl bg-white/10 ring-1 ring-white/10 flex items-center justify-center shadow-lg"><logo/></div>
                    <div class="flex flex-col">
                        <span class="text-white text-[21px] font-bold tracking-tight">{{ t('app_name')}}</span>
                        <span class="text-[11px] text-white/45 tracking-wide">CONTROL CENTER</span>
                    </div>
                </div>
                <div class="px-3 pb-6">
                    <template v-for="(entry, idx) in visibleMenu" :key="idx">
                        <menu-item
                            v-if="entry.type === 'item'"
                            :href="entry.href"
                            :title="t(entry.titleKey)"
                        >
                            <i class="menu-icon tf-icons" :class="`${entry.icon} text-[22px]`"></i>
                        </menu-item>

                        <menu-group
                            v-else
                            :title="t(entry.titleKey)"
                            :items="entry.children"
                        >
                            <template #icon>
                                <i class="tf-icons" :class="`${entry.icon} text-[22px]`"></i>
                            </template>

                            <menu-item
                                v-for="child in entry.children.filter(canSeeChild)"
                                :key="child.href"
                                :href="child.href"
                                :title="t(child.titleKey)"
                            >
                                <i class="menu-icon tf-icons" :class="`${child.icon} text-[22px]`"></i>
                            </menu-item>
                        </menu-group>
                    </template>
                </div>
            </div>
        </custom-scroll>
    </div>
</template>

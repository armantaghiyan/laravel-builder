<script setup lang="ts">
import {Permissions} from "@/utils/models/enums.ts";

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
            <div v-if="$app.isOpenSidebar && !isXl" @click="$app.isOpenSidebar = false" class="bg-gray-4/50 w-full h-screen fixed to-pink-50 right-0 left-0 z-40"></div>
        </fade-animate>

        <aside class="fixed bg-menu-theme text-menu-color w-65 duration-200 h-full z-40" :class="{'inset-s-0': $app.isOpenSidebar, '-inset-s-65': !$app.isOpenSidebar}">
            <div>
                <div class="h-16 flex items-center gap-2 ps-5.5 pe-2">
                    <logo/>
                    <span class="text-white text-[22px] font-bold">{{ t('app_name')}}</span>
                </div>
                <div class="px-3">
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
        </aside>
    </div>
</template>

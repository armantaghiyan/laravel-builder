import {createRouter, createWebHistory} from "vue-router";

import {appStore} from "@/stores/app.ts";
import {userStore} from "@/stores/user.ts";

import HomePage from "@/pages/home/index.vue";
import LoginPage from "@/pages/auth/login.vue";

import AccessIndexPage from "@/pages/access/index.vue";
import AccessCreatePage from "@/pages/access/create.vue";
import AccessShowPage from "@/pages/access/show.vue";

import AdminIndexPage from "@/pages/admin/index.vue";
import AdminCreatePage from "@/pages/admin/create.vue";
import AdminShowPage from "@/pages/admin/show.vue";

import SampleChartPage from "@/pages/sample/chart.vue";
import SampleButtonPage from "@/pages/sample/button.vue";

const routes = [
    {path: "/login", name: "LoginPage", component: LoginPage},

    {path: "/", name: "HomePage", component: HomePage},

    {path: "/access", name: "AccessIndexPage", component: AccessIndexPage},
    {path: "/access/create/:id?", name: "AccessCreatePage", component: AccessCreatePage},
    {path: "/access/:id", name: "AccessShowPage", component: AccessShowPage},

    {path: "/admin", name: "AdminIndexPage", component: AdminIndexPage},
    {path: "/admin/create/:id?", name: "AdminCreatePage", component: AdminCreatePage},
    {path: "/admin/:id", name: "AdminShowPage", component: AdminShowPage},

    {path: '/chart', name: 'SampleChartPage', component: SampleChartPage},
    {path: '/button', name: 'SampleButtonPage', component: SampleButtonPage},
];

const router = createRouter({
    history: createWebHistory('/admin'),
    routes,
    // @ts-ignore
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition;
        }

        return {top: 0, behavior: 'smooth'};
    }
});



// @ts-ignore
router.beforeEach(async (to: RouteLocationNormalized, from) => {
    const $user = userStore();
    const $app = appStore();

    if (!$user.isAuth && $user.tryGetUser === 0) {
        $user.tryGetUser = 1;
        await $user.checkAuth();
    }

    $app.stopLoading();
    if (!$user.isAuth && to.path !== '/login') {
        return { path: '/login' };
    }

    if ($user.isAuth && to.path === '/login') {
        return { path: '/' };
    }

    return true;
});

export default router;

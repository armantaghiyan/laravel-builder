<script setup lang="ts">
const { t } = useTranslations();

const {login, form, pending} = useAuthLogin();
</script>

<template>
    <div>
        <div class="grid lg:grid-cols-5 grid-cols-1 min-h-screen">
            <div class="relative lg:flex flex-col hidden lg:col-span-3 login-visual items-center justify-center overflow-hidden">
                <div class="flex absolute inset-s-10 top-9 text-[22px] items-center z-10 text-white">
                    <logo/>
                    <span class="ps-3 font-bold">{{ t('app_name') }}</span>
                </div>
                <div class="relative z-10 flex flex-col items-center">
                    <img src="/resources/assets/images/bg/auth-login.png" alt="auth login" class="max-w-120 py-5 drop-shadow-2xl">
                    <p class="mt-3 max-w-100 text-center text-white/75 text-[15px] leading-7">مدیریت شفاف‌تر امور مالی، در یک فضای امن و یکپارچه.</p>
                </div>
                <img src="/resources/assets/images/bg/bg-shape-image-light.png" alt="" class="absolute w-full h-80 bottom-0">
            </div>
            <div class="relative lg:col-span-2 flex items-center justify-center p-6 sm:p-12 bg-white">
                <div class="w-full">
                    <div class="max-w-105 mx-auto">
                        <span class="inline-flex items-center rounded-full bg-light-primary px-3 py-1 text-[12px] font-bold text-primary">ADMIN PORTAL</span>
                        <h4 class="mt-4 mb-2 text-start text-[28px] font-bold tracking-tight">{{ t('auth.welcome') }}</h4>
                        <p class="mb-8 text-gray-500">{{ t('auth.login_desc') }}</p>
                        <form @submit.prevent="login" class="flex flex-col gap-5">
                            <text-input id="username" :title="t('auth.username')" :placeholder="t('auth.enter_username')" v-model="form.username"/>
                            <text-input id="password" type="password" :title="t('auth.password')" placeholder="············" v-model="form.password"/>
                            <app-button type="submit" variant="primary" :loading="pending" class="w-full !h-11.5 !rounded-xl !shadow-[0_10px_20px_rgba(99,91,255,0.25)]">{{ t('auth.sin_in') }}</app-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.login-visual {
    background: radial-gradient(circle at 85% 12%, rgba(148, 142, 255, .35), transparent 24%), linear-gradient(135deg, #302b63 0%, #453b9d 48%, #216e96 100%);
}
</style>

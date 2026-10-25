<script setup lang="ts">
const { t } = useTranslations();

const {login, form, pending, retryAfter, captcha, captchaPending, captchaFailed, captchaError, refreshCaptcha} = useAuthLogin();
</script>

<template>
    <div class="min-h-screen bg-panel">
        <div class="grid lg:grid-cols-5 grid-cols-1 min-h-screen">
            <div class="relative lg:flex flex-col hidden lg:col-span-3 login-visual items-center justify-center overflow-hidden">
                <div class="flex absolute inset-s-10 top-9 text-[22px] items-center z-10 text-white">
                    <logo/>
                    <span class="ps-3 font-bold">{{ t('app_name') }}</span>
                </div>
                <div class="relative z-10 flex flex-col items-center">
                    <img src="/resources/assets/images/bg/auth-login.png" alt="auth login" class="max-w-120 py-5 drop-shadow-2xl">
                </div>
                <img src="/resources/assets/images/bg/bg-shape-image-light.png" alt="" class="absolute w-full h-80 bottom-0">
            </div>
            <div class="relative lg:col-span-2 flex items-center justify-center p-6 sm:p-12 bg-white">
                <div class="absolute top-0 left-0 right-0 flex justify-end p-2">
                    <app-header-action/>
                </div>

                <div class="w-full">
                    <div class="max-w-105 mx-auto">
                        <span class="inline-flex items-center rounded-full bg-light-primary px-3 py-1 text-[12px] font-bold text-primary">ADMIN PORTAL</span>
                        <h4 class="mt-4 mb-2 text-start text-[28px] font-bold tracking-tight">{{ t('auth.welcome') }}</h4>
                        <p class="mb-8 text-gray-500">{{ t('auth.login_desc') }}</p>
                        <form @submit.prevent="login" class="flex flex-col gap-5">
                            <text-input id="username" name="username" autocomplete="username" required :maxlength="40" :disabled="pending" :title="t('auth.username')" :placeholder="t('auth.enter_username')" v-model="form.username"/>
                            <text-input id="password" name="password" autocomplete="current-password" required :maxlength="40" :disabled="pending" type="password" :title="t('auth.password')" placeholder="············" v-model="form.password"/>
                            <div class="rounded-lg border border-gray-200 bg-gray-50/60 p-3">
                                <div class="mb-3 flex items-center gap-2">
                                    <div class="flex h-20 min-w-0 flex-1 items-center justify-center overflow-hidden rounded-md border border-gray-200 bg-white" :aria-busy="captchaPending">
                                        <img v-if="captcha" :src="captcha.image" :alt="t('auth.captcha_image')" width="240" height="80" :draggable="false" class="h-20 max-w-full object-contain"/>
                                        <span v-else role="status" class="px-3 text-center text-xs text-gray-500">{{ t(captchaFailed ? 'auth.captcha_failed' : 'auth.captcha_loading') }}</span>
                                    </div>
                                    <app-button type="button" outline icon-only :title="t('auth.captcha_refresh')" :aria-label="t('auth.captcha_refresh')" :loading="captchaPending" :disabled="pending || retryAfter > 0" class="h-10! w-10! shrink-0" @click="refreshCaptcha">
                                        <i class="ti ti-refresh text-xl" aria-hidden="true"></i>
                                    </app-button>
                                </div>
                                <text-input id="captcha" name="captcha" autocomplete="off" required :maxlength="6" :disabled="pending || captchaPending || !captcha" :title="t('auth.captcha_code')" :placeholder="t('auth.captcha_placeholder')" v-model="form.captcha" class="captcha-input"/>
                                <p v-if="captchaError" role="alert" class="mt-2 text-xs text-danger">{{ captchaError }}</p>
                                <p class="mt-2 text-xs text-gray-500">{{ t('auth.captcha_hint') }}</p>
                            </div>
                            <p v-if="retryAfter > 0" role="status" class="text-sm text-danger">{{ t('auth.retry_after', {seconds: retryAfter}) }}</p>
                            <app-button type="submit" variant="primary" :loading="pending" :disabled="retryAfter > 0 || captchaPending || !captcha" class="w-full h-11.5! rounded-xl! shadow-[0_10px_20px_rgba(99,91,255,0.25)]!">{{ t('auth.sin_in') }}</app-button>
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

.captcha-input :deep(input) {
    text-transform: uppercase;
    letter-spacing: .25em;
    text-align: center;
}

.captcha-input :deep(input::placeholder) {
    letter-spacing: normal;
}
</style>

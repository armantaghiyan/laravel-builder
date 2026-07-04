import axios from "axios";
import {errorToast} from "@/utils/toastify.ts";
import {appStore} from "@/stores/app.ts";
import {useCookie} from "@/composables/useCookie.ts";
import {useTranslations} from "@/composables/useTranslations.ts";

export function useCallApi() {
    const apiToken = useCookie('api_token');
    const {locale} = useTranslations();

    const $app = appStore();
    const pending = ref(false);

    const callApi = axios.create({
        baseURL: `${window.location.origin}/admin/`,
        timeout: 30000,
        headers: {
            'Accept': 'application/json',
        },
    });

    function detectOS(): string {
        const ua = navigator.userAgent;
        if (/windows/i.test(ua)) return 'windows';
        if (/android/i.test(ua)) return 'android';
        if (/iphone|ipad|ipod/i.test(ua)) return 'ios';
        if (/mac/i.test(ua)) return 'mac';
        if (/linux/i.test(ua)) return 'linux';
        return 'Unknown';
    }

    callApi.interceptors.request.use((config) => {
        pending.value = true;

        if (apiToken.value.value) {
            config.headers.Authorization = `Bearer ${apiToken.value.value}`;
        }

        config.headers['Accept-Language'] = locale.value;

        config.headers['X-Timestamp'] = Date.now().toString();
        config.headers['X-App-Version'] = '1.0.0';
        config.headers['X-OS'] = detectOS();

        return config;
    }, (error) => {
        pending.value = false;
        $app.hideLoading();

        return Promise.reject(error);
    });

    callApi.interceptors.response.use((response) => {
        $app.hideLoading();
        pending.value = false;

        return response;
    }, (error) => {
        $app.hideLoading();
        pending.value = false;

        try {
            if(error?.response?.data?.result === 'error_validation'){
                addErrorToInput(error?.response?.data?.errors);
            }else if(error?.response?.data?.result === 'error_message'){
                errorToast(error?.response?.data?.message);
            }else if(error?.response?.data?.result === 'rate_limit'){
                errorToast(error?.response?.data?.message);
            }
        }catch (e) {}

        return Promise.reject(error);
    });

    function addErrorToInput(errors: object){
        Object.entries(errors).map(error => {
            const id = error[0];
            const message = error[1][error[1].length - 1];

            const element = document.getElementById(id);
            if(element){
                const errorMessageElement = element.querySelector('.error-message');
                if(!errorMessageElement){
                    const newDiv = document.createElement('div');
                    newDiv.textContent = message;
                    newDiv.classList.add('error-message');
                    element.appendChild(newDiv);


                    let inputChild;
                    if (element?.classList?.contains('custom-multiselect')) {
                        inputChild = element.querySelector('.custom-multiselect-content');
                    } else {
                        inputChild = element.querySelector('input');
                    }

                    inputChild?.classList.add('error-input');
                }
            }
        })
    }

    function objectToFormData(obj: Record<string, any>, method: 'post'|'patch'|null = null): FormData {
        const formData = new FormData();
        if(method){
            formData.append('_method', method)
        }

        Object.entries(obj).forEach(([key, value]) => {
            if (value instanceof File) {
                formData.append(key, value);
            } else if (typeof value === 'boolean' || typeof value === 'number') {
                formData.append(key, value.toString());
            } else if (value !== undefined && value !== null) {
                formData.append(key, value);
            }
        });

        return formData;
    }

    return {
        callApi,
        pending,
        objectToFormData
    }
}

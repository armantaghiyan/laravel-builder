import {appStore} from "@/stores/app.ts";

export function showLoading() {
    appStore().showLoading();
}

export function hideLoading() {
    appStore().hideLoading();
}

export function formatMoney(amount: number, withUnit: boolean = false): string {
    return amount.toLocaleString() + (withUnit ? ' تومان ' : '');
}

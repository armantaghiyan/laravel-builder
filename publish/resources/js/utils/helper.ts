import {appStore} from "@/stores/app.ts";

export function showLoading() {
    appStore().showLoading();
}

export function hideLoading() {
    appStore().hideLoading();
}

export function formatMoney(amount: number, withUnit: boolean = true): string {
    return amount.toLocaleString() + (withUnit ? ' تومان ' : '');
}


export function shortenIP(ip: string | null): string | null {
    if (ip != null && ip.includes(':')) {
        const parts = ip.split(':');

        if (parts.length > 2) {
            return `${parts[0]}:${parts[1]}:...:${parts[parts.length - 2]}:${parts[parts.length - 1]}`;
        }
    }

    return ip;
}

export function truncateText(text: string, maxLength: number): string {
    if (text?.length > maxLength) {
        return text?.slice(0, maxLength) + '...';
    }

    return text;
}

export function chunkArray<T>(arr: T[], size: number): T[][] {
    const result: T[][] = [];

    for (let i = 0; i < arr.length; i += size) {
        result.push(arr.slice(i, i + size));
    }

    return result;
}

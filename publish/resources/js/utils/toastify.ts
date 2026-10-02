import Toastify from "toastify-js";
import "toastify-js/src/toastify.css";

type ToastType = "success" | "error";

export function toast(message: string) {
    showToast(message, "", "success");
}

export function errorToast(message: string, errorTitle = "") {
    showToast(message, errorTitle, "error");
}

const ICONS: Record<ToastType, string> = {
    error: "/admin-assets/icons/ic_error.svg",
    success: "/admin-assets/icons/ic_success.svg",
};

function escapeHtml(str: string): string {
    return str
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#39;");
}

function createToastMessage(message: string, errorTitle: string, type: ToastType): string {
    return `
        <div class="toast-content">
            <img class="toast-icon" src="${ICONS[type]}" alt="" />
            <span class="toast-divider"></span>
            <div class="toast-text">
                ${errorTitle ? `<div class="toast-title">${escapeHtml(errorTitle)}</div>` : ""}
                <div class="toast-message">${escapeHtml(message)}</div>
            </div>
        </div>
    `;
}

function showToast(message: string, errorTitle: string, type: ToastType) {
    try {
        const isError = type === "error";

        const instance = Toastify({
            text: createToastMessage(message, errorTitle, type),
            duration: isError ? 6000 : 3000,
            newWindow: false,
            close: false,
            gravity: "bottom",
            position: "center",
            stopOnFocus: true,
            escapeMarkup: false,
            className: `custom-toast custom-toast-${type}`,
        });

        instance.showToast();

        const toastElement = (instance as any).toastElement as HTMLElement | undefined;

        const handleOutsideClick = (e: MouseEvent) => {
            if (!toastElement || !document.body.contains(toastElement)) {
                document.removeEventListener("click", handleOutsideClick, true);
                return;
            }

            if (!toastElement.contains(e.target as Node)) {
                instance.hideToast();
                document.removeEventListener("click", handleOutsideClick, true);
            }
        };

        setTimeout(() => {
            document.addEventListener("click", handleOutsideClick, true);
        }, 0);
    } catch (e) {
        console.error("Toast error:", e);
    }
}

import Toastify from "toastify-js";
import "toastify-js/src/toastify.css";

type ToastType = "success" | "error";

export function toast(message: string) {
    showToast(message, "", "success");
}

export function errorToast(message: string, errorTitle = "") {
    showToast(message, errorTitle, "error");
}

function showToast(message: string, errorTitle: string, type: ToastType) {
    console.log('showToast');


    try {
        const isError = type === "error";

        const toast = Toastify({
            text: createToastMessage(message, errorTitle, type),
            duration: isError ? 6000 : 3000,
            newWindow: false,
            close: false,
            gravity: "bottom",
            position: "center",
            stopOnFocus: true,
            escapeMarkup: false,
            className: `custom-toast custom-toast-${type}`,
            style: {
                background: isError ? "#e62a19" : "#66BB6A",
                ...(isError && { borderRadius: "15px" }),
            },
        });

        toast.showToast();

        const handleOutsideClick = (e: MouseEvent) => {
            const toastElement = document.querySelector(".toastify");

            if (!toastElement) {
                document.removeEventListener("click", handleOutsideClick, true);
                return;
            }

            if (!toastElement.contains(e.target as Node)) {
                if (typeof (toast as any).hideToast === "function") {
                    (toast as any).hideToast();
                } else {
                    toastElement.remove();
                }

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

function createToastMessage(message: string, errorTitle: string, type: ToastType): string {
    let icon = "";
    let borderColor = "";

    if (type === "error") {
        icon = "/admin-assets/icons/ic_error.svg";
        borderColor = "#F55F6F";
    } else {
        icon = "/admin-assets/icons/ic_success.svg";
        borderColor = "#29CD87";
    }

    return `
        <div style="display:flex;align-items:center;justify-content:space-between;width:100%">
            <div style="flex: none;">
                <img src="${icon}" alt="" style="width:40px" />
            </div>
            <div style="border-left:1px solid ${borderColor};height:32px;margin:0 16px;"></div>
            <div>
                ${errorTitle ? `<div>${errorTitle}</div>` : ""}
                <div style="font-size:14px">${message}</div>
            </div>
        </div>
    `;
}

import Toastify from 'toastify-js';

export function toast(message: string) {
    try {
        Toastify({
            text: message,
            duration: 3000,
            // destination: "https://github.com/apvarun/toastify-js",
            newWindow: false,
            close: false,
            gravity: "bottom",
            position: "center",
            stopOnFocus: true,
            className: 'custom-toast',
            style: {
                background: "#66BB6A",
            },
            onClick: function () {
            }
        }).showToast();
    } catch (e) {
    }
}

export function errorToast(message: string) {
    try {
        Toastify({
            text: message,
            duration: 3000,
            // destination: "https://github.com/apvarun/toastify-js",
            newWindow: false,
            close: false,
            gravity: "bottom",
            position: "center",
            stopOnFocus: true,
            className: 'custom-toast',
            style: {
                background: "#E64A19",
            },
            onClick: function () {
            }
        }).showToast();
    } catch (e) {}
}

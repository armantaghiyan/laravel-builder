import Swal from 'sweetalert2'
import {useI18n} from "vue-i18n";

export function useAlert() {
    const { t } = useI18n()

    async function confirm(): Promise<boolean> {
        const result = await Swal.fire({
            title: t('confirm.title'),
            text: t('confirm.text'),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28c76f',
            cancelButtonColor: '#ff4c51',
            confirmButtonText: t('confirm.confirm'),
            cancelButtonText: t('confirm.cancel')
        })

        return result.isConfirmed
    }

    return {
        confirm
    }
}

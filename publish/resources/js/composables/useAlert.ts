import Swal from 'sweetalert2'

export function useAlert() {
    const { t } = useTranslations()

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

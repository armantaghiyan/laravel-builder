import Swal from 'sweetalert2'

export function useAlert() {
    const { t } = useTranslations()

    async function confirm(): Promise<boolean> {
        const result = await Swal.fire({
            title: t('confirm.title'),
            text: t('confirm.text'),
            background: 'var(--color-surface)',
            color: 'var(--color-foreground)',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: getTailwindColor('danger'),
            cancelButtonColor: getTailwindColor('dark'),
            focusCancel: true,
            reverseButtons: true,
            confirmButtonText: t('confirm.confirm'),
            cancelButtonText: t('confirm.cancel')
        })

        return result.isConfirmed
    }

    return {
        confirm
    }
}

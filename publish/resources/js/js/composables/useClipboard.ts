import {errorToast, toast} from "@/utils/toastify.ts";
import {useTranslations} from "@/composables/useTranslations.ts";

export const useClipboard = () => {
    const {t} = useTranslations();

    const copyToClipboard = async (text: string | number)  => {
        try {
            await navigator.clipboard.writeText(text.toString())
            toast(t('global.copy_message'));
        } catch (error) {
            errorToast(t('global.copy_failed'));
        }
    }

    return {copyToClipboard}
}

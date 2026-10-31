import BaseResponse from "@/utils/api/base.ts";
import {SettingValue} from "@/utils/models/Setting.ts";

export default function useSettingUpdate() {
    const {callApi, pending} = useCallApi();

    function update(key: string, value: SettingValue) {
        return callApi.patch<BaseResponse>('/setting', {key, value});
    }

    return {update, pending};
}

export type SettingValue = string | number | boolean | null;

export default interface Setting {
    key: string;
    group: string;
    type: 'text' | 'textarea' | 'integer' | 'boolean';
    default: SettingValue;
    value: SettingValue;
}

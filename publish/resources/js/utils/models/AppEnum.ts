export interface AppEnum {
    log_levels: AppEnumStruct[]
    log_user_guard: AppEnumStruct[]
    log_event: AppEnumStruct[]
    log_loggable_type: AppEnumStruct[]
}

export interface AppEnumStruct {
    value: string,
    label: string,
    color: string,
}

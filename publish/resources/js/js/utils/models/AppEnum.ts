export interface AppEnum {
    user_status: AppEnumStruct[]
    payment_status: AppEnumStruct[]
    transaction_target_type: AppEnumStruct[]
    sms_confirmation_type: AppEnumStruct[]
}

export interface AppEnumStruct {
    value: string,
    label: string,
    color: string,
}

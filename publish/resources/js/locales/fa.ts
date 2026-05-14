export default {
    app_name: 'پنل',
    app: {
        en: 'انگلیسی',
        fa: 'فارسی',
        filter: 'فیلتر',
        all: 'همه',
    },
    global: {
        id: "شناسه",
        name: "نام",
        status: "وضعیت",
        search: "جستجو",
        add: "افزودن",
        customers: "کاربر",
        price: "مبلغ",
        date: "تاریخ",
        actions: "عملیات ها",
        edit: "ویرایش",
        update: "بروزرسانی",
        copy_message: "متن با موفقیت کپی شد",
        copy_message_fail: "کپی ناموفق بود",
        username: "نام کاربری",
        created_at: 'تاریخ ایجاد',
        updated_at: 'تاریخ بروزرسانی',
        submit: 'ثبت',
        user_id: 'شناسه کاربری',
        title: 'عنوان',
        target_id: 'شناسه مقصد',
        target_type: 'مقصد',
        description: 'توضیحات',
        gift: 'افزایش اعتبار',
        type: 'نوع'
    },
    auth: {
        welcome: 'به پنل خوش آمدید! 👋',
        login_desc: 'لطفا وارد حساب کاربری خود شوید و ماجراجویی را شروع کنید',
        username: 'نام کاربری',
        enter_username: 'نام کاربری خود را وارد کنید',
        password: 'رمز عبور',
        repeat_password: 'تکرار رمز عبور',
        sin_in: 'ورود',
        sin_up: 'ثبت نام',
        new_in_platform: 'آیا حساب کاربری دارید؟',
        create_account: 'ایجاد حساب',
        already_have_an_account: 'قبلا حساب کاربری داشته اید؟',
        sign_in_instead: 'ورود به حساب کاربری',
        register_desc_1: 'ماجراجویی از اینجا شروع می شود 🚀',
        register_desc_2: 'مدیریت برنامه خود را آسان و سرگرم کننده کنید!',
    },
    menu: {
        dashboard: 'داشبورد',
        settings: 'تنظیمات',
        list: 'لیست',
        admin: 'مدیران',
        access: 'نقش ها و دسترسی ها',
        model: 'مدل ها',
        user: 'کاربران',
        financial: 'مالی',
        payments: 'پرداخت ها',
        chats: 'چت ها',
        transactions: 'تراکنش ها',
        sms_confirmation: 'کد های تایید',
    },
    admin: {
        last_login: 'آخرین ورود',
        admin_detail: 'جزئیات ادمین',
        add_a_admin: 'اضافه کردن مدیر جدید',
        admin_information: 'اطلاعات مدیر',
        roles: 'نقش ها',
    },
    list: {},
    pagination: {
        desc: "نمایش {p1} تا {p2} از {p3} ورودی"
    },
    confirm: {
        title: "آیا اطمینان دارید؟",
        text: "آیا از انجام این عملیات اطمینان دارید؟",
        confirm: "بله",
        cancel: "انصراف"
    },
    roles: {
        admin: 'مدیران',
        role: 'نقش ها',
        ai_model: 'مدل ها',
        user: 'کاربران',
        "Super Admin": 'مدیر کل',
    },
    access: {
        permissions: 'دسترسی ها',
        permission: {
            admin: {
                index: "لیست مدیران",
                store: "ایجاد مدیر",
                update: "ویرایش مدیران",
                add_role: "افزودن نقش",
            },
            role: {
                index: "لیست نقش ها",
                store: "ایجاد نقش",
                update: "بروزرسانی نقش ها",
                destroy: "حذف نقش ها",
            },
            ai_model: {
                index: "لیست مدل ها",
                store: "ایجاد مدل",
                update: "بروزرسانی مدل ها",
            },
            user: {
                index: "لیست کاربران",
                update: "بروزرسانی کاربرها",
            },
            payment: {
                index: "لیست پرداخت ها",
            },
            chat: {
                index: "لیست چت ها",
            },
            transaction: {
                index: "لیست تراکنش ها",
            },
            sms_confirmation_index: {
                index: "لیست پیامک های اعتبار سنجی",
            }
        }
    },
    ai_model: {
        model_name: 'مدل',
        input_price: 'قیمت توکن ورودی',
        output_price: 'قیمت توکن خروجی',
        price_desc: 'قیمت ها برای هر 1 میلیون توکن می باشد.',
        add_a_model: 'اضافه کردن مدل جدید',
        model_information: 'اصلاعات مدل',
        thumbnail: 'عکس',
    },
    user: {
        mobile_number: 'شماره موبایل',
        balance: 'اعتبار حساب',
        reserved_balance: 'اعتبار برداشت نشده',
        image: 'عکس',
        user_detail: 'جزئیات کاربر',
        active: 'فعال سازی کاربر',
        banned: 'مسدود سازی کاربر',
    },
    payment: {
        price: "مبلغ",
        detail: "جزئیات پرداخت",
    },
    chat: {
        input_token: 'توکن ورودی',
        output_token: 'توکن خروجی',
        total_price: 'قیمت کل',
    },
    smsConfirmation: {
        token: 'کد تایید'
    }
}

<?php
return [
    'siteEmail' => 'zakaz@bulkabakery.ru',
    'adminEmail' => 'zakaz@bulkabakery.ru',
    'supportEmail' => 'zakaz@bulkabakery.ru',
    'noReplyEmail' => 'no-reply@bulkabakery.ru',
    'user.passwordResetTokenExpire' => 3600,

    'payMethods' => [
        'online' => 'Онлайн оплата',
//        'card' => 'Картой курьеру',
//        'cash' => 'Наличными курьеру',
//        'cash_on_self' => 'Наличными при самовывозе',
//        'card_on_self' => 'Картой при самовывозе',
        'contract' => 'По договору',
    ],

    'deliveryMethods' => [
        'delivery' => 'Доставка',
        'pickup' => 'Самовывоз',
    ],

    'cacheTime' => 1,
    'defaultLanguage' => 'ru',

    //old
    //'sberBankUserName' => 'bulkabakery-api',
    //'sberBankPassword' => 'qD9W8Nv^K5*x',
    //new
    'sberBankUserName' => 'P9706010102-api',
    'sberBankPassword' => 'Bf4u%L8x!3',
    'sberBankUserNameTest' => 'T9706010102-api',
    'sberBankPasswordTest' => 'T9706010102',
    //use username & password OR token
    //'sberBankToken' => '',

    //sms ru api key
    'sms_ru_api_id' => 'FE01C3B7-4215-990B-794B-FFBA44C535A0',
];

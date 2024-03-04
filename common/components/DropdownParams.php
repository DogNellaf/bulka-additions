<?php

namespace common\components;

use yii\base\BaseObject;

class DropdownParams extends BaseObject
{
    public $ratio = [
        '1' => '1',
        '2' => '2',
    ];

    public $socials = [
        'fb' => 'Фейсбук',
        'in' => 'Инстаграм',
        'vk' => 'Вконтакте',
        'you' => 'Ютуб'
    ];

    public $contact_types = [
        'phone' => 'Телефон',
        'envelope' => 'Почта',
        'pointer' => 'Адрес',
        'time' => 'Время работы',
        'other' => 'Другое',
    ];

    public $pay_methods = [
        'cash' => 'Наличными курьеру',
//        'online' => 'PayPal',
    ];

}
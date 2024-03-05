<?php

namespace frontend\forms;

use yii\base\Model;
use common\entities\User;

class LoyaltyConfirmForm extends Model
{
    public $phone;

    public function rules()
    {
        return [
            ['phone', 'trim'],
            ['phone', 'required'],
            ['phone', 'string', 'min' => 18, 'max' => 18],
            ['phone', 'unique', 'targetClass' => '\common\entities\User', 'message' => 'Этот номер телефона уже занят другим пользователем.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'phone' => 'Номер телефона',
        ];
    }

    public function sendConfirmationCode()
    {
        if (!$this->validate()) {
            return null;
        }
        // TO DO
        return  User::signup($this->username, $this->email, $this->password);
    }
}

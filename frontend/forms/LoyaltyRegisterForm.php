<?php

namespace frontend\forms;

use yii\base\Model;
use common\entities\User;

class LoyaltyRegisterForm extends Model
{
    public $phone;

    public function rules()
    {
        return [
            ['phone', 'trim'],
            ['phone', 'required'],
            ['phone', 'string', 'min' => 15, 'max' => 15],
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
        return User::signup($this->username, $this->email, $this->password);
    }
}

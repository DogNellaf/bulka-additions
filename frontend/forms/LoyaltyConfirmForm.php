<?php

namespace frontend\forms;

use yii\base\Model;
use common\entities\User;
use common\entities\LoyaltyApi;

class LoyaltyConfirmForm extends Model
{
    public $code;

    public function rules()
    {
        return [
            ['num1', 'required'],
            ['num1', 'integer'],

            ['num2', 'required'],
            ['num2', 'integer'],

            ['num3', 'required'],
            ['num3', 'integer'],

            ['num4', 'required'],
            ['num4', 'integer'],
        ];
    }

    public function checkCode()
    {
        if (!$this->validate()) {
            return null;
        }

        $loyalty = new LoyaltyApi();

        return $loyalty.
    }
}

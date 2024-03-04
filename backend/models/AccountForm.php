<?php
namespace backend\models;

use yii\base\Model;
use yii;

class AccountForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $password_confirm;

    public function rules()
    {
        return [
            ['username', 'required'],
            ['username', 'string', 'min' => 1, 'max' => 255],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique',
                'targetClass'=> '\common\entities\User',
                'message' => 'Этот E-mail уже занят другим пользователем.',
                'filter' => function ($query) {
                    /* @var $query yii\db\Query */
                    $query->andWhere(['not', ['id' => Yii::$app->user->getId()]]);
                }
            ],
            ['password', 'string'],
            [['password_confirm'], 'compare', 'compareAttribute' => 'password']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя',
            'email' => 'Email',
            'password' => 'Пароль',
            'password_confirm' => 'Подтверждение пароля'
        ];
    }

    public function account()
    {
        if (!$this->validate()) {
            return null;
        }
        /* @var $user \common\entities\User */
        $user = Yii::$app->user->identity;
        $user->username = $this->username;
        $user->email = $this->email;
        if ($this->password) {
            $user->setPassword($this->password);
        }
        return $user->save() ? true : false;
    }
}
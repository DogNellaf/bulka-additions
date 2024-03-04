<?php

namespace frontend\forms;

use Yii;
use yii\base\Model;
use common\entities\User;

class PasswordResetRequestForm extends Model
{
    public $email;

    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\entities\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'Пользователь с таким E-mail не найден'
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'E-mail',
        ];
    }

    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        return Yii::$app->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['noReplyEmail'] => 'Пекарня и ресторан Булка'])
            ->setTo($this->email)
            ->setSubject('Сброс пароля для Пекарня и ресторан Булка')
            ->send();
    }
}

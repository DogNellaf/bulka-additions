<?php
namespace frontend\forms;

use yii\base\Model;
use HttpInvalidParamException;
use common\entities\User;

class ResetPasswordForm extends Model
{
    public $password;

    private $_user;


    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new HttpInvalidParamException('Password reset token cannot be blank.');
        }
        $this->_user = User::findByPasswordResetToken($token);
        if (!$this->_user) {
            throw new HttpInvalidParamException('Wrong password reset token.');
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => 'Новый пароль',
        ];
    }

    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();

        return $user->save(false);
    }
}

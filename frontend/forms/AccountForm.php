<?php

namespace frontend\forms;

use common\components\CompositeForm;
use common\entities\User;
use common\entities\UserAddress;

/**
 * @property UserAddress $addresses
 */
class AccountForm extends CompositeForm
{
    public $username;
    public $phone;
    public $email;

    private $_phone;
    private $_email;
    private $_user;

    public function __construct(array $config = [])
    {
        /* @var User $user */
        parent::__construct($config);
        $user = \Yii::$app->user->identity;
        $this->_user = $user;
        $this->_email = $user->email;
        $this->_phone = $user->phone;
        $this->username = $user->username;
        $this->phone = $user->phone;
        $this->email = $user->email;
        $this->addresses = $user->userAddresses;
    }

    public function rules()
    {
        return [
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::class, 'filter' => $this->_email ? ['not like', 'email', $this->_email] : null, 'message' => 'Этот e-mail адрес уже занят.'],

            ['phone', 'string', 'max' => 255],
            ['phone', 'unique', 'targetClass' => User::class, 'filter' => $this->_phone ? ['not like', 'phone', $this->_phone] : null, 'message' => 'Этот телефон уже занят.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'ФИО',
            'email' => 'E-mail',
            'phone' => 'Телефон',
        ];
    }

    public function editAccount(): User
    {
        if (!$this->validate()) {
            return null;
        }
        /* @var User $user */
        $user = \Yii::$app->user->identity;

        $user->edit($this->username, $this->phone, $this->email);

        foreach ($this->addresses as $address) {
            $address->save();
        }
        return $user->save() ? $user : $this->_user;
    }

    public function internalForms(): array
    {
        return ['addresses'];
    }
}
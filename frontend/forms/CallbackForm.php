<?php

namespace frontend\forms;

use common\entities\Callbacks;
use Yii;
use yii\base\Model;

class CallbackForm extends Model
{
    public $name;
    public $phone;
    public $email;
    public $notes;
    public $verifyCode;
    public $data_collection_checkbox;


    public function rules()
    {
        return [
            [['name', 'phone', 'email', 'notes'], 'required'],
            [['name', 'phone', 'email'], 'string', 'max' => 255],
            [['notes'], 'string'],
            ['verifyCode', 'captcha', 'captchaAction'=>'site/captcha'],
            [['data_collection_checkbox'], 'required', 'requiredValue' => 1, 'message' => Yii::t('app', 'Подтвердите Условия'),],
            [['email'], 'email'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Имя'),
            'phone' => Yii::t('app', 'Телефон'),
            'email' => Yii::t('app', 'E-mail'),
            'notes' => Yii::t('app', 'Текст сообщения'),
            'verifyCode' => Yii::t('app', 'Проверочный код'),
        ];
    }

    public function create()
    {
        if (!$this->validate()) {
            return null;
        }
        return Callbacks::create($this->name, $this->phone, $this->email, $this->notes);
    }

    public function mail($respond)
    {
        $this->sendToAdmin($respond);
        if ($this->email) {
            $this->sendToCustomer($respond);
        }
    }

    private $adminHtml;
    private $customerHtml;

    public function sendToAdmin($respond)
    {
        /* @var $respond Callbacks */

        $this->adminHtml .= "<style>";
        $this->adminHtml .= ".h2{ font-size:2em; font-weight:lighter; text-transform:uppercase;}";
        $this->adminHtml .= "</style>";
        $this->adminHtml .= "<table>";
        $this->adminHtml .= "<tr><td colspan='2' class='form-heading' ><h2>Сообщение</h2></td><td></td></tr>";
        $this->adminHtml .= "<tr><td>Имя</td><td>: {$respond->name}</td></tr>";
        $this->adminHtml .= "<tr><td>Телефон</td><td>: {$respond->phone}</td></tr>";
        $this->adminHtml .= "<tr><td>E-mail</td><td>: {$respond->email}</td></tr>";
        $this->adminHtml .= "<tr><td>Текст сообщения</td><td>: {$respond->notes}</td></tr>";
        $this->adminHtml .= "</table>";

        $sent = Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['noReplyEmail'])
            ->setTo(Yii::$app->params['adminEmail'])
            ->setSubject('Сообщение от ' . $this->name)
            ->setHtmlBody($this->adminHtml)
            ->send();

        if (!$sent) {
            throw new \RuntimeException('Ошибка отправки E-mail.');
        }
    }

    public function sendToCustomer($respond)
    {
        /* @var $respond Callbacks */

        $this->customerHtml .= "<style>";
        $this->customerHtml .= ".h2{ font-size:2em; font-weight:lighter; text-transform:uppercase;}";
        $this->customerHtml .= "</style>";
        $this->customerHtml .= "<h2>" . Yii::t('app', 'Ваше сообщение принято') . "</h2>";
        $this->customerHtml .= "<table>";
        $this->customerHtml .= "<tr><td>" . Yii::t('app', 'Имя') . "</td><td>: {$respond->name}</td></tr>";
        $this->customerHtml .= "<tr><td>" . Yii::t('app', 'Телефон') . "</td><td>: {$respond->phone}</td></tr>";
        $this->customerHtml .= "<tr><td>" . Yii::t('app', 'E-mail') . "</td><td>: {$respond->email}</td></tr>";
        $this->customerHtml .= "<tr><td>" . Yii::t('app', 'Текст сообщения') . "</td><td>: {$respond->notes}</td></tr>";
        $this->customerHtml .= "</table>";

        $sent = Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['noReplyEmail'])
            ->setTo($this->email)
            ->setSubject(Yii::t('app', 'Ваше сообщение принято') . ': ' . Yii::$app->name)
            ->setHtmlBody($this->customerHtml)
            ->send();

        if (!$sent) {
            throw new \RuntimeException('Ошибка отправки E-mail.');
        }
    }}
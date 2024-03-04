<?php

namespace frontend\forms;

use common\entities\Reserves;
use common\entities\Restaurants;
use Yii;
use yii\base\Model;

class ReserveForm extends Model
{
    public $name;
    public $phone;
    public $restaurant_id;
    public $date;
    public $persons;
    public $notes;
    public $verifyCode;
    public $data_collection_checkbox;


    public function rules()
    {
        return [
            [['name', 'phone', 'restaurant_id', 'date', 'persons'], 'required'],
            [['restaurant_id', 'persons'], 'integer'],
            [['notes'], 'string'],
            [['name', 'phone', 'date'], 'string', 'max' => 255],
            ['verifyCode', 'captcha', 'captchaAction'=>'site/captcha'],
            [['data_collection_checkbox'], 'required', 'requiredValue' => 1, 'message' => Yii::t('app', 'Подтвердите Условия'),],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'phone' => 'Телефон',
            'restaurant_id' => 'Ресторан',
            'date' => 'Дата',
            'persons' => 'Количество гостей',
            'notes' => 'Комментарии',
            'verifyCode' => Yii::t('app', 'Проверочный код'),
        ];
    }

    public function create()
    {
        if (!$this->validate()) {
            return null;
        }
        return Reserves::create($this->name, $this->phone, $this->restaurant_id, $this->date, $this->persons, $this->notes);
    }

    public function mail($respond)
    {
        $this->sendToAdmin($respond);
        /*
        if ($this->email) {
            $this->sendToCustomer($respond);
        }
        */
    }

    private $adminHtml;
    private $customerHtml;

    public function sendToAdmin($respond)
    {
        /* @var $respond Reserves */
        /* @var $restaurant Restaurants */
        $restaurant = Restaurants::findOne($respond->restaurant_id);

        $this->adminHtml .= "<style>";
        $this->adminHtml .= ".h2{ font-size:2em; font-weight:lighter; text-transform:uppercase;}";
        $this->adminHtml .= "</style>";
        $this->adminHtml .= "<table>";
        $this->adminHtml .= "<tr><td colspan='2' class='form-heading' ><h2>Резерв</h2></td><td></td></tr>";
        $this->adminHtml .= "<tr><td>Имя</td><td>: {$respond->name}</td></tr>";
        $this->adminHtml .= "<tr><td>Телефон</td><td>: {$respond->phone}</td></tr>";
        $this->adminHtml .= "<tr><td>Ресторан</td><td>: {$restaurant->title}</td></tr>";
        $this->adminHtml .= "<tr><td>Дата</td><td>: {$respond->date}</td></tr>";
        $this->adminHtml .= "<tr><td>Количество гостей</td><td>: {$respond->persons}</td></tr>";
        $this->adminHtml .= "<tr><td>Комментарии</td><td>: {$respond->notes}</td></tr>";
        $this->adminHtml .= "</table>";

        $sendTo = Yii::$app->params['adminEmail'];
        if ($restaurant->email) {
            $sendTo = $restaurant->email;
        }

        $sent = Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['noReplyEmail'])
            ->setTo($sendTo)
            ->setSubject('Резерв от ' . $this->name)
            ->setHtmlBody($this->adminHtml)
            ->send();

        if (!$sent) {
            throw new \RuntimeException('Ошибка отправки E-mail.');
        }
    }

    public function sendToCustomer($respond)
    {
        /* @var $respond Reserves */
        /*
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
        */
    }
}
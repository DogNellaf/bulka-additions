<?php

namespace common\entities;

use common\models\smsru\SMSRU;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\View;

/**
 * This is the model class for table "{{%orders}}".
 *
 * @property int $id
 * @property int $user_id
 * @property int $quantity
 * @property float $cost
 * @property float $delivery_cost
 * @property int $created_at
 * @property int $updated_at
 * @property string $name
 * @property string $email
 * @property string $address
 * @property string $street
 * @property string $house
 * @property string $apartment
 * @property string $entrance
 * @property string $intercom
 * @property string $floor
 * @property string $phone
 * @property string $datetime
 * @property string $pay_method
 * @property string $delivery_method
 * @property string $note
 * @property string $delivery_pickup_point
 * @property string $cart_json
 * @property int $status
 * @property int $user_status
 * @property int $xml_formed
 * @property string $acquirer_order_id
 * @property string $sms_result
 * @property string $email_result
 * @property string $ref_url
 * @property int $end_payment_reached
 *
 * @property string $delivery_date
 * @property string $delivery_time
 *
 * @property OrderItems[] $orderItems
 * @property User $user
 */
class Orders extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%orders}}';
    }

    const STATUSES = [
        0 => 'Ожидает',
        1 => 'Выполнен',
    ];

    public function behaviors()
    {
        return [
            'class' => TimestampBehavior::class,
        ];
    }

    public function rules()
    {
        return [
            [['name', 'phone', 'email'], 'required'],
            [['pay_method', 'delivery_method'], 'required'],
            [['cost', 'quantity'], 'required'],
            [['user_id'], 'integer'],
            [['quantity'], 'integer'],
            [['cost', 'delivery_cost'], 'number'],
            [['note', 'datetime', 'cart_json'], 'string'],
            [['name', 'phone', 'pay_method', 'delivery_method'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 20],
            [['email', 'address'], 'string', 'max' => 255],
            [['street', 'house', 'apartment', 'floor', 'entrance', 'intercom'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['cart_json'], 'safe'],
            [['xml_formed'], 'integer'],
            [['delivery_pickup_point'], 'string', 'max' => 255],
            [['acquirer_order_id'], 'string', 'max' => 255],
            [['sms_result'], 'string', 'max' => 1024],
            [['email_result'], 'string', 'max' => 1024],
            [['ref_url'], 'string', 'max' => 1024],
            [['end_payment_reached'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'quantity' => 'Количество товаров',
            'cost' => 'Сумма',
            'delivery_cost' => 'Стоимость доставки',
            'created_at' => 'Оформлен',
            'updated_at' => 'Обработан',
            'datetime' => 'Дата и время доставки',
            'delivery_date' => 'Дата доставки/самовывоза',
            'delivery_time' => 'Время доставки/самовывоза',
            'name' => 'Имя',
            'email' => 'E-mail',
            'phone' => 'Телефон',
            'address' => 'Адрес доставки',
            'street' => 'Улица',
            'house' => 'Дом',
            'apartment' => 'Квартира/офис',
            'intercom' => 'Домофон',
            'floor' => 'Этаж',
            'entrance' => 'Подъезд',
            'note' => 'Комментарии',
            'status' => 'Статус',
            'user_status' => 'Доступно к удалению',
            'pay_method' => 'Способ оплаты',
            'delivery_method' => 'Способ доставки',
            'delivery_pickup_point' => 'Пункт самовывоза',
            'acquirer_order_id' => 'id в системе эквайера',
            'sms_result' => 'Результат отправки смс',
            'email_result' => 'Результат отправки email',
            'ref_url' => 'Ссылка-реферер',
        ];
    }

    public function getOrderItems()
    {
        return $this->hasMany(OrderItems::class, ['order_id' => 'id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function mail()
    {
        $this->sendToAdmin();
        if ($this->email) {
            $this->sendToCustomer();
        }
    }

    public function sendToAdmin()
    {
        $adminHtml = '';
        $payMethods = Yii::$app->params['payMethods'];
        $deliveryMethods = Yii::$app->params['deliveryMethods'];
        $created_at = Yii::$app->formatter->asDatetime($this->created_at, 'dd-MM-yyyy H:m');
        $summaryCost = $this->cost + $this->delivery_cost;

        $adminHtml .= "<style>";
        $adminHtml .= ".h2{ font-size:2em; font-weight:lighter; text-transform:uppercase;}";
        $adminHtml .= "</style>";
        $adminHtml .= "<table>";
        $adminHtml .= "<tr><td>Оформлен</td><td>: {$created_at}</td></tr>";
        $adminHtml .= "<tr><td>Способ оплаты</td><td>: {$payMethods[$this->pay_method]}</td></tr>";
        $adminHtml .= "<tr><td>Способ доставки</td><td>: {$deliveryMethods[$this->delivery_method]}</td></tr>";
        $adminHtml .= "<tr><td colspan='2' class='form-heading' ><h2>Клиент</h2></td><td></td></tr>";
        $adminHtml .= "<tr><td>Имя</td><td>: {$this->name}</td></tr>";
        $adminHtml .= $this->email ? "<tr><td>E-Mail</td><td>: {$this->email}</td></tr>" : null;
        $adminHtml .= "<tr><td>Телефон</td><td>: {$this->phone}</td></tr>";
        $adminHtml .= "<tr><td>Улица</td><td>: {$this->street}</td></tr>";
        $adminHtml .= "<tr><td>Дом</td><td>: {$this->house}</td></tr>";
        $adminHtml .= "<tr><td>Квартира/офис</td><td>: {$this->apartment}</td></tr>";
        $adminHtml .= "<tr><td>Домофон</td><td>: {$this->intercom}</td></tr>";
        $adminHtml .= "<tr><td>Подъезд</td><td>: {$this->entrance}</td></tr>";
        $adminHtml .= "<tr><td>Этаж</td><td>: {$this->floor}</td></tr>";
        $adminHtml .= "<tr><td>Время доставки</td><td>: {$this->delivery_date} {$this->delivery_time} </td></tr>";
        $adminHtml .= $this->note ? "<tr><td>Комментарии</td><td>: {$this->note}</td></tr>" : null;
        $adminHtml .= "</table>";

        $adminHtml .= "<h4>Список блюд:</h4>";
        $adminHtml .= "<table>";
        $adminHtml .= "<tr><td>id</td><td>Название</td><td> Количество</td><td> Цена</td></tr>";
        foreach ($this->orderItems as $item) {
            $adminHtml .= "<tr><td>{$item->product_id}</td><td>{$item->title}</td><td>{$item->qty_item}  шт.</td><td> {$item->price_item} руб.</td></tr>";
        }
        $adminHtml .= "<tr><td></td><td></td><td>Стоимость</td><td>: {$this->cost}</td></tr>";
        $adminHtml .= "<tr><td></td><td></td><td>Стоимость доставки</td><td>: {$this->delivery_cost}</td></tr>";
        $adminHtml .= "<tr><td></td><td></td><td>Итого</td><td>: {$summaryCost}</td></tr>";
        $adminHtml .= "</table>";

        $setTo = Yii::$app->params['adminEmail'];
        $setCc = Yii::$app->params['adminEmail'];

        $today = Yii::$app->formatter->asDatetime((time()), 'dd.MM.yyyy');
        $tomorrow = Yii::$app->formatter->asDatetime((time() + 24 * 60 * 60), 'dd.MM.yyyy');
        if ($this->delivery_method == 'pickup') {
            $setCc = Restaurants::findOne($this->delivery_pickup_point)->email;
        } elseif ($tomorrow == $this->delivery_date && date('H') >= 18) {
            $setCc = Restaurants::findOne(1)->email;
        } elseif ($today == $this->delivery_date) {
            //todo temp ; централизованно на Покровку
            $setCc = Restaurants::findOne(1)->email;
            //$setCc = ArrayHelper::getColumn(Restaurants::find()->andWhere(['status' => 1])->orderBy('sort')->all(), 'email');
        }


        $sent = Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['noReplyEmail'])
            ->setTo($setTo)
            ->setCc($setCc)
            ->setSubject('Заказ от ' . $this->name)
            ->setHtmlBody($adminHtml)
            ->send();

        if (!$sent) {
            throw new \RuntimeException('Ошибка отправки E-mail.');
        }
    }

    public function sendToCustomer()
    {
        $customerHtml = '';

        $payMethods = Yii::$app->params['payMethods'];
        $deliveryMethods = Yii::$app->params['deliveryMethods'];
        $date = $this->datetime ? Yii::$app->formatter->asDatetime($this->datetime, 'dd-MM-yyyy H:m') : null;

        $customerHtml .= "<style>";
        $customerHtml .= ".h2{ font-size:2em; font-weight:lighter; text-transform:uppercase;}";
        $customerHtml .= "</style>";
        $customerHtml .= "<h2>Ваш заказ принят</h2>";
        $customerHtml .= "<table>";
        $customerHtml .= "<tr><td>Имя</td><td>: {$this->name}</td></tr>";
        $customerHtml .= "<tr><td>Телефон</td><td>: {$this->phone}</td></tr>";
        $customerHtml .= "<tr><td>Улица</td><td>: {$this->street}</td></tr>";
        $customerHtml .= "<tr><td>Дом</td><td>: {$this->house}</td></tr>";
        $customerHtml .= "<tr><td>Квартира/офис</td><td>: {$this->apartment}</td></tr>";
        $customerHtml .= "<tr><td>Домофон</td><td>: {$this->intercom}</td></tr>";
        $customerHtml .= "<tr><td>Подъезд</td><td>: {$this->floor}</td></tr>";
        $customerHtml .= "<tr><td>Этаж</td><td>: {$this->entrance}</td></tr>";
        $customerHtml .= $date ? "<tr><td>Время доставки</td><td>: {$date}</td></tr>" : null;
        $customerHtml .= "<tr><td>Способ оплаты</td><td>: {$payMethods[$this->pay_method]}</td></tr>";
        $customerHtml .= "<tr><td>Способ доставки</td><td>: {$deliveryMethods[$this->delivery_method]}</td></tr>";
        $customerHtml .= $this->note ? "<tr><td>Комментарии</td><td>: {$this->note}</td></tr>" : null;
        $customerHtml .= "</table>";

        $customerHtml .= "<h4>Список блюд:</h4>";
        $customerHtml .= "<table>";
        $customerHtml .= "<tr><td>Название</td><td> Количество</td><td> Цена</td></tr>";
        foreach ($this->orderItems as $item) {
            $customerHtml .= "<tr><td>{$item->title}</td><td>{$item->qty_item}  шт.</td><td> {$item->price_item} руб.</td></tr>";
        }
        $customerHtml .= "<tr><td></td><td>Итого</td><td>: {$this->cost}</td></tr>";
        $customerHtml .= "</table>";
        /*
        $sent = Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['noReplyEmail'])
            ->setTo($this->customer_email)
            ->setSubject('Подтверждение заказа с сайта: ' . Yii::$app->name)
            ->setHtmlBody($customerHtml)
            ->send();
        */
        $sent = Yii::$app->mailer->compose('@frontend/views/layouts/emails/order_customer_html', [
            'order' => $this,
        ])
            ->setFrom([Yii::$app->params['noReplyEmail'] => 'BulkaBakery'])
            ->setTo($this->email)
            ->setSubject('Подтверждение заказа с сайта: ' . Yii::$app->name)
            //->setHtmlBody($customerHtml)
            ->send();

        if (!$sent) {
            throw new \RuntimeException('Ошибка отправки E-mail.');
        }
    }

    public function formXml()
    {
        $view = new View();
        $xmlContent = $view->render('@frontend/views/layouts/form_order_xml', [
            'order' => $this,
        ]);
        //$xml = str_replace(["\n", "\r", "\t"], '', $xmlContent);
        $filePath = Yii::getAlias('@orders_xml') . '/bulka_' . $this->id . '.xml';
        $localFilePath = Yii::getAlias('@orders_xml_local') . '/bulka_' . $this->id . '.xml';
        file_put_contents($filePath, $xmlContent);
        file_put_contents($localFilePath, $xmlContent);
        if (file_exists($localFilePath)) {
            return true;
        }
        return false;
    }

    public function sendSMS()
    {
        if (!$this->phone) {
            $this->sms_result = 'Не найден номер телефона';
            $this->save();
            return false;
        }

        $code = 'Спасибо за Ваш заказ №' . $this->id . ' на сайте Булки. Заказ принят в работу. Если у вас есть вопросы или вы хотите отменить заказ, пожалуйста, позвоните +7(495)2307017 доб. 4. Ваша Булка';

        $smsru = new SMSRU(Yii::$app->params['sms_ru_api_id']); // Ваш уникальный программный ключ, который можно получить на главной странице
        $data = new \stdClass();
        $data->to = $this->phone;
        $data->text = $code; // Текст сообщения
        // $data->from = ''; // Если у вас уже одобрен буквенный отправитель, его можно указать здесь, в противном случае будет использоваться ваш отправитель по умолчанию
        // $data->time = time() + 7*60*60; // Отложить отправку на 7 часов
        // $data->translit = 1; // Перевести все русские символы в латиницу (позволяет сэкономить на длине СМС)
        $data->test = 1; // Позволяет выполнить запрос в тестовом режиме без реальной отправки сообщения
        // $data->partner_id = '1'; // Можно указать ваш ID партнера, если вы интегрируете код в чужую систему
        $sms = $smsru->send_one($data); // Отправка сообщения и возврат данных в переменную

        if ($sms->status == "OK") { // Запрос выполнен успешно
            //echo "Сообщение отправлено успешно. ";
            //echo "ID сообщения: $sms->sms_id. ";
            //echo "Ваш новый баланс: $sms->balance";
            $result = 'OK';
        } else {
            //echo "Сообщение не отправлено. ";
            //echo "Код ошибки: $sms->status_code. ";
            //echo "Текст ошибки: $sms->status_text.";
            $result = $sms->status_code . ': ' . $sms->status_text;
        }
        $this->sms_result = $result;
        $this->save();
        return $result;
    }

}

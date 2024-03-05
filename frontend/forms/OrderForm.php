<?php

namespace frontend\forms;

use common\entities\OrderItems;
use common\entities\Orders;
use common\entities\Restaurants;
use common\entities\UserAddress;
use common\models\CartItem;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;
use common\models\Cart;
use common\entities\User;

class OrderForm extends Model
{
    public $customer_name;
    public $customer_email;
    public $customer_phone;
    public $customer_pickup_name;
    public $customer_pickup_phone;
    public $customer_pickup_email;

    public $address;
    public $save_address;
    public $street;
    public $elementary_street;
    public $elementary_house;
    public $house;
    public $apartment;
    public $floor;
    public $entrance;
    public $intercom;
    public $delivery_date;
    public $delivery_time;
    public $delivery_quickly;
    public $delivery_cost;
    public $delivery_pickup_point;
    public $note;
    public $payMethod;
    public $deliveryMethod;

    public $verifyCode;
    public $data_collection_checkbox;

    private $cart;

    public function __construct(Cart $cart, $config = [])
    {
        parent::__construct($config);
        $this->cart = $cart;
        if (!Yii::$app->user->isGuest) {
            /* @var $user User */
            $user = Yii::$app->user->identity;

            $this->customer_name = $user->userProfile->getFullName();
            $this->customer_pickup_name = $user->userProfile->getFullName();
            $this->customer_email = $user->email;
            $this->customer_phone = $user->phone;
            $this->customer_pickup_phone = $user->phone;
            $this->customer_pickup_email = $user->email;

            $this->address = $user->userAddresses[0]->value;
            $this->street = $user->userAddresses[0]->street;
            $this->house = $user->userAddresses[0]->house;
            $this->apartment = $user->userAddresses[0]->apartment;
            $this->entrance = $user->userAddresses[0]->entrance;
            $this->intercom = $user->userAddresses[0]->intercom;
            $this->floor = $user->userAddresses[0]->floor;
            $this->note = $user->userAddresses[0]->note;
        }
    }

    public function rules()
    {
        return [
            [['address', 'note', 'customer_name', 'customer_pickup_name', 'street', 'elementary_street', 'elementary_house', 'house', 'apartment', 'floor', 'entrance', 'intercom'], 'filter', 'filter' => function ($value) {
                return HtmlPurifier::process($value);
            }],
            [['note'], 'string'],
            [['payMethod', 'deliveryMethod'], 'required'],
            [['customer_name', 'customer_pickup_name', 'customer_email', 'customer_pickup_email', 'address', 'street', 'elementary_street', 'elementary_house', 'house', 'apartment', 'floor', 'entrance', 'intercom'], 'string', 'max' => 255],
            [['customer_email', 'customer_pickup_email'], 'email'],
            [['delivery_date', 'delivery_time'], 'required'],
            [['delivery_date', 'delivery_time'], 'string', 'max' => 50],

            [['delivery_date'], 'date', 'format' => 'dd.MM.yyyy'],

            //[['customer_name', 'customer_pickup_name', 'customer_email', 'customer_pickup_email'], 'string', 'max' => 250],
            [['customer_phone', 'customer_pickup_phone'], 'string', 'max' => 20],

            //['verifyCode', 'captcha', 'captchaAction' => 'site/captcha', 'on' => 'create'],
            [['data_collection_checkbox'], 'required', 'requiredValue' => 1, 'message' => Yii::t('app', 'Your Approve Required'),],
            [['save_address'], 'integer'],
            [['delivery_cost'], 'number'],
            [['delivery_pickup_point'], 'string', 'max' => 250],

            [['customer_name', 'customer_phone', 'customer_email'], 'required', 'when' => function ($model) {
                return $model->deliveryMethod == 'delivery';
            }, 'whenClient' => "function (attribute, value) {
            return $('.delivery_radio:checked').val() == 'delivery';
            }"],
            [['customer_pickup_name', 'customer_pickup_phone', 'customer_pickup_email'], 'required', 'when' => function ($model) {
                return $model->deliveryMethod == 'pickup';
            }, 'whenClient' => "function (attribute, value) {
            return $('.delivery_radio:checked').val() == 'pickup';
            }"],

        ];
    }

    public function attributeLabels()
    {
        return [
            'customer_name' => 'ФИО',
            'customer_pickup_name' => 'ФИО',
            'customer_email' => 'E-mail',
            'customer_pickup_email' => 'E-mail',
            'customer_phone' => 'Телефон',
            'customer_pickup_phone' => 'Телефон',
            'address' => 'Адрес доставки',
            'street' => 'Улица/дом',
            'house' => 'Дом',
            'apartment' => 'Квартира/офис',
            'floor' => 'Этаж',
            'intercom' => 'Домофон',
            'entrance' => 'Подъезд',
            'delivery_date' => 'Дата',
            'delivery_time' => 'Время',
            'delivery_quickly' => 'Как можно быстрее',
            'delivery_cost' => 'Стоимость доставки',
            'save_address' => 'Сохранить адрес',
            'note' => 'Комментарии',
            'payMethod' => 'Способ оплаты',
            'deliveryMethod' => 'Способ доставки',
            'verifyCode' => 'Проверочный код',
            'data_collection_checkbox' => 'Согласие на обработку персональных данных',
            'delivery_pickup_point' => 'Пункт самовывоза',
        ];
    }

    public function create()
    {
        $order = new Orders();
        if (!Yii::$app->user->isGuest) {
            /* @var $user User */
            $user = Yii::$app->user->identity;
            $order->user_id = $user->id;
            $newUserPhone = ($this->customer_phone) ? $this->customer_phone : $this->customer_pickup_phone;
            if (!$user->phone && !User::findOne(['phone' => $newUserPhone])) {
                $user->phone = $newUserPhone;
                $user->save();
            }
            if ($this->save_address) {
                if (!$user->userAddresses[0]->value
                    && !$user->userAddresses[0]->street
                    && !$user->userAddresses[0]->house
                    && !$user->userAddresses[0]->apartment
                    && !$user->userAddresses[0]->floor
                    && !$user->userAddresses[0]->entrance
                    && !$user->userAddresses[0]->intercom
                    && !$user->userAddresses[0]->note
                ) {
                    $newAddress = $user->userAddresses[0];
                }
                elseif ($existAddress = UserAddress::findOne([
                    'user_id' => $user->id,
                    'street' => $this->elementary_street,
                    'house' => $this->house,
                    'apartment' => $this->apartment,
                    'floor' => $this->floor,
                    'entrance' => $this->entrance,
                    'intercom' => $this->intercom,
                    'note' => $this->note
                ])) {
                    $newAddress = $existAddress;
                }
                else {
                    $newAddress = new UserAddress();
                    $newAddress->user_id = $user->id;
                }
                $newAddress->street = $this->elementary_street;
                $newAddress->house = $this->house;
                $newAddress->apartment = $this->apartment;
                $newAddress->floor = $this->floor;
                $newAddress->entrance = $this->entrance;
                $newAddress->intercom = $this->intercom;
                $newAddress->note = $this->note;
                $newAddress->save();
            }
        }
        $order->name = ($this->deliveryMethod == 'delivery') ? $this->customer_name : $this->customer_pickup_name;
        $order->phone = ($this->deliveryMethod == 'delivery') ? $this->customer_phone : $this->customer_pickup_phone;
        $order->email = ($this->deliveryMethod == 'delivery') ? $this->customer_email : $this->customer_pickup_email;

        $order->address = $this->address;
        $order->street = $this->elementary_street;
        $order->house = $this->elementary_house;
        $order->apartment = $this->apartment;
        $order->floor = $this->floor;
        $order->entrance = $this->entrance;
        $order->intercom = $this->intercom;
        $order->delivery_date = $this->delivery_date;
        $order->delivery_time = $this->delivery_time;

        /*
        $deliveryDate = Yii::$app->formatter->asDatetime(time(), 'dd.MM.yyyy');
        $deliveryTime = '00:00';
        if (!$this->delivery_quickly) {
            if ($this->delivery_date) {
                $deliveryDate = $this->delivery_date;
            }
            if ($this->delivery_time) {
                $deliveryTime = $this->delivery_time;
            }
        }
        $deliveryDateTime = \DateTime::createFromFormat('d.m.Y H:i', $deliveryDate . ' ' . $deliveryTime);
        if ($deliveryDateTime) {
            $order->datetime = $deliveryDateTime->getTimestamp() . '';
        } else {
            $order->datetime = null;
        }
        */
        if ($this->deliveryMethod == 'pickup') {
            $order->delivery_pickup_point = $this->delivery_pickup_point;
        }

        $order->pay_method = $this->payMethod;
        $order->delivery_method = $this->deliveryMethod;
        $order->quantity = $this->cart->getTotalAmount();
        $order->cost = $this->cart->getCost();
        $order->delivery_cost = $this->delivery_cost;
        $order->note = $this->note;
        $order->cart_json = $this->cart->setArrayJson($this->cart);

        $order->ref_url = Yii::$app->session['orig_ref'];

        if ($order->save()) {
            $this->saveOrderItems($this->cart->getItems(), $order->id);
        } else {
            return false;
        }
        return $order;
    }

    protected function saveOrderItems($items, $order_id)
    {
        /* @var $item CartItem */
        // $discountPerItem = $cart->getBonuses() / count($items);
        foreach ($items as $id => $item) {
            $order_item = new OrderItems();
            $order_item->order_id = $order_id;
            $order_item->product_id = $item->getProductId();
            $order_item->title = $item->getProduct()->title;
            $order_item->qty_item = $item->quantity;
            $order_item->price_item = $item->getPrice(); //- $discountPerItem;
            $order_item->weight = $item->weight;
            $order_item->options = $item->options;
            $order_item->save();
        }
    }

}
<?php
namespace common\models;

use common\entities\Orders;
use Yii;

class SberBank
{
    //testing mode
    //const GATEWAY_URL = 'https://3dsec.sberbank.ru/payment/rest/';
    //prod mode
    //const GATEWAY_URL = 'https://securepayments.sberbank.ru/payment/rest/';
    const RETURN_URL = 'https://bulkabakery.ru/cart/end-payment';
    const FAIL_URL = 'https://bulkabakery.ru/cart/fail-payment';

    function getEnvironments($test = false) {
        //prod mode
        $gateway_url = 'https://securepayments.sberbank.ru/payment/rest/';
        $userName = Yii::$app->params['sberBankUserName'];
        $password = Yii::$app->params['sberBankPassword'];

        if ($test) {
        //if (Yii::$app->request->userIP == "188.163.21.92") {
            //test mode
            $gateway_url = 'https://3dsec.sberbank.ru/payment/rest/';
            $userName = Yii::$app->params['sberBankUserNameTest'];
            $password = Yii::$app->params['sberBankPasswordTest'];
        }


        return [
            'gateway_url' => $gateway_url,
            'userName' => $userName,
            'password' => $password,
        ];
    }

    function gateway($method, $data) {
        $curl = curl_init(); // Инициализируем запрос
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->getEnvironments()['gateway_url'].$method, // Полный адрес метода
            CURLOPT_RETURNTRANSFER => true, // Возвращать ответ
            CURLOPT_POST => true, // Метод POST
            CURLOPT_POSTFIELDS => http_build_query($data) // Данные в запросе
        ));
        $response = curl_exec($curl); // Выполняем запрос

        $response = json_decode($response, true); // Декодируем из JSON в массив
        curl_close($curl); // Закрываем соединение
        return $response; // Возвращаем ответ
    }

    //doc: https://securepayments.sberbank.ru/wiki/doku.php/integration:api:rest:requests:register
    public function register($orderId) {
        /* @var $order Orders */
        if (!$order = Orders::findOne($orderId)) {
            return false;
        }

        $items = [];
        $i = 0;
        foreach ($order->orderItems as $orderItem) {
            $items[$i] = [
                "positionId" => $orderItem->id,
                "name" => $orderItem->title . ' ' . $orderItem->getWeight()->title,
                "quantity" => [
                    "value" => $orderItem->qty_item,
                    "measure" => "шт"
                ],
                //"itemAmount" => $orderItem->price_item * $orderItem->qty_item * 100,
                "itemPrice" => $orderItem->price_item * 100,
                "itemCode" => $orderItem->getWeight()->id_1c,
            ];

            $i++;
        }

        //delivery
        if ($order->delivery_cost) {
            $items[] = [
                "positionId" => 0,
                "name" => 'Доставка',
                "quantity" => [
                    "value" => 1,
                    "measure" => "услуга"
                ],
                "itemPrice" => $order->delivery_cost * 100,
                "itemCode" => 'delivery',
            ];
        }

        $orderBundle = [
            "cartItems" => [
                "items" => $items,
            ]
        ];

        $orderBundleJson = json_encode($orderBundle);

        $data = array(
            'userName' => $this->getEnvironments()['userName'],
            'password' => $this->getEnvironments()['password'],
            //'token' => Yii::$app->params['sberBankToken'],
            'orderNumber' => $orderId,
            'amount' => ($order->cost + $order->delivery_cost) * 100,
            'returnUrl' => self::RETURN_URL . '?localOrderId=' . $orderId,
            'failUrl' => self::FAIL_URL . '?localOrderId=' . $orderId,
            'orderBundle' => $orderBundleJson,
        );
        $response = self::gateway('register.do', $data);
        if (isset($response['errorCode'])) { // В случае ошибки вывести ее
            return $response;
//            return false;
//            return 'Ошибка #' . $response['errorCode'] . ': ' . $response['errorMessage'];
        } else { // В случае успеха перенаправить пользователя на платежную форму
            header('Location: ' . $response['formUrl']);
            die();
        }
    }

    public function registerTest($orderId) {

        $orderBundle = [
            "customerDetails" =>
                [
                    "email" => "null@gmail.ru",
                    "phone" => "79313670160",
                    "fullName" => "ООО Наш покупатель",
                    "inn" => 7801392271
                ],
            "cartItems" => [
                "items" => [
                    [
                        "positionId" => "1",
                        "name" => "TEST",
                        "quantity" => ["value" => 1.0, "measure" => "psc"],
                        "itemAmount" => 1000,
                        "itemCode" => "code1",
                        "itemPrice" => "1000",
                        "itemAttributes" => [
                            "attributes" => [
                                ["name" => "agent_info.paying.operation", "value" => "Test operation"],
                                ["name" => "supplier_info.phones", "value" => "9161234567"],
                                ["name" => "agent_info.MTOperator.name", "value" => "Test MT Operator"],
                                ["name" => "agent_info.paymentsOperator.phones", "value" => "9161234568,"],
                                ["name" => "nomenclature", "value" => "dGVzdCBkZXBvc2l0"],
                                ["name" => "agent_info.MTOperator.address", "value" => "Moscow"],
                                ["name" => "supplier_info.name", "value" => "Наименование поставщика"],
                                ["name" => "paymentMethod", "value" => "1"],
                                ["name" => "paymentObject", "value" => "3"],
                                ["name" => "agent_info.MTOperator.phones", "value" => "9161234569"],
                                ["name" => "agent_info.MTOperator.inn", "value" => "169910020020"],
                                ["name" => "supplier_info.inn", "value" => "012345678900"],
                                ["name" => "agent_info.type", "value" => "1"],
                                ["name" => "agent_info.paying.phones", "value" => "9161234560"]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $orderBundleJson = json_encode($orderBundle);

        $data = array(
            'userName' => $this->getEnvironments()['userName'],
            'password' => $this->getEnvironments()['password'],
            'orderNumber' => $orderId,
            'returnUrl' => self::RETURN_URL,
            'amount' => 1000,
            'orderBundle' => $orderBundleJson,
        );
        $response = self::gateway('register.do', $data);
        if (isset($response['errorCode'])) { // В случае ошибки вывести ее
            return $response;
//            return false;
            //return 'Ошибка #' . $response['errorCode'] . ': ' . $response['errorMessage'];
        } else { // В случае успеха перенаправить пользователя на платежную форму
            header('Location: ' . $response['formUrl']);
            die();
        }
    }

    public function orderStatus($bankOrderId) {
        $data = array(
            'userName' => $this->getEnvironments()['userName'],
            'password' => $this->getEnvironments()['password'],
            //'token' => Yii::$app->params['sberBankToken'],
            'orderId' => $bankOrderId,
        );

        $response = self::gateway('getOrderStatusExtended.do', $data);
        return $response;
    }
}
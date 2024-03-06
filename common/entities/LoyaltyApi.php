<?php

namespace common\entities;

use yii;
use yii\base\Component;
use yii\httpclient\Client;

class LoyaltyApi extends Component
{
    protected function getClearedPhone($phone) {
        $phone = str_replace("+","",$phone);
        $phone = str_replace(" ","",$phone);
        $phone = str_replace("-","",$phone);
        $phone = str_replace("(","",$phone);
        return str_replace(")","",$phone);
    }

    protected function log($data) {
        Yii::info('[loyalty] '.$success);
    }

    protected function getHttpClient()
    {
        $token = Yii::$app->params['loyalty']['token'];
        $url = Yii::$app->params['loyalty']['url'];
        $client = new Client(['baseUrl' => $url]);
        return $client
                ->createRequest()
                ->setMethod('POST')
                ->setFormat(Client::FORMAT_JSON)
                ->setHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => $token,
                ]);
    }

    protected function sendRequest($url, $data) {
        return $this
                ->getHttpClient()
                ->setUrl($url)
                ->setData($data)
                ->send()
                ->data;
    }

    // buyer-info-detail request
    public function getInfo($phone)
    {
        $phone = $this->getClearedPhone($phone);
        $data = $this->sendRequest('buyer-info-detail', ['identificator' => $phone]);

        $success = $data['success'];

        $this->log($success);

        if ($success == True) {
            return $data;
        }

        Yii::error($data['error_description']);
        return $success;
    }

    // buyer-register request
    public function register($user)
    {
        $phone = $this->getClearedPhone($user->phone);
        $data = $this
                ->sendRequest('buyer-register', 
                [
                    'phone' => $phone,
                    'name' => $user->username,
                    'email' => $user->email
                ]);

        $success = $data['success'];
        $this->log('[loyalty] '.$data);
        if ($success == False) {
            Yii::error($data['error_description']);
        }
        return $data;
    }

    // buyer-edit request
    public function editBuyer($user)
    {
        $phone = $this->getClearedPhone($user->phone);
        $data = $this
                ->sendRequest('buyer-edit', 
                [
                    'phone' => $phone,
                    'name' => $user->username,
                    'email' => $user->email
                ]);

        $success = $data['success'];
        $this->log('[loyalty] '.$data);
        if ($success == False) {
            Yii::error($data['error_description']);
        }
        return $data;
    }

    // write-off-request request
    public function getWriteOff($card, $user)
    {
        $phone = $this->getClearedPhone($user->phone);

        $items = [];

        foreach ($cart->getItems() as $item) {
            $product = $item->getProduct();
            $quantity = $item->getQuantity();
            $cost = $item->getCost();
            array_push($items, [
                'amount' => $quantity * $cost,
                'quantity' => $quantity,
                'cost' => $cost,
                'name' => $product->title,
                'external_item_id' => $product->id
            ]);
        }

        $body = [
            'phone' => $phone,
            'write_off_bonus' => $card->getBonuses(),
            'items' => $items
        ];

        $data = $this->sendRequest('write-off-request', $body);

        $success = $data['success'];
        $this->log('[loyalty] '.$data);
        if ($success == False) {
            Yii::error($data['error_description']);
        }
        return $data;
    }

    // purchase request
    public function purchase($order)
    {
        $user = $order->user;
        $cart = $order->cart;
        $phone = $this->getClearedPhone($user->phone);

        $items = [];

        foreach ($cart->getItems() as $item) {
            $product = $item->getProduct();
            $quantity = $item->getQuantity();
            $cost = $item->getCost();
            array_push($items, [
                'amount' => $quantity * $cost,
                'quantity' => $quantity,
                'cost' => $cost,
                'name' => $product->title,
                'external_item_id' => $product->id
            ]);
        }

        $body = [
            'phone' => $phone,
            'external_purchase_id' => $order->id,
            'write_off_bonus' => $cart->getBonuses(),
            'items' => $items
        ];

        $data = $this->sendRequest('purchase', $body);

        $success = $data['success'];
        $this->log('[loyalty] '.$data);
        if ($success == False) {
            Yii::error($data['error_description']);
        }
        return $data;
    }

    // change-purchase-status request
    // public function changePurchaseStatus($purchase_id, $external_purchase_id, $status)
    // {
    //     throw new NotFoundHttpException('Запрошенная вами страница не существует.');
    // }

    // edit-purchase request
    public function editPurchase($order)
    {
        $user -> $order->user;
        $phone = $this->getClearedPhone($user->phone);

        $items = [];

        foreach ($cart->getItems() as $item) {
            $product = $item->getProduct();
            $quantity = $item->getQuantity();
            $cost = $item->getCost();
            array_push($items, {
                'amount' => $quantity * $cost,
                'quantity' => $quantity,
                'cost' => $cost,
                'name' => $product->title,
                'external_item_id' => $product->id
            });
        }

        $body = [
            'phone' => $phone,
            'external_purchase_id' => $order->id,
            'write_off_bonus' => $order->getBonuses(),
            'items' => $items
        ]

        $data = $this->sendRequest('edit-purchase', $body);

        $success = $data['success'];
        $this->log('[loyalty] '.$data);
        if ($success == False) {
            Yii::error($data['error_description']);
        }
        return $data;
    }

    // cancel-purchase request
    public function cancelPurchase($id)
    {
        $data = $this->sendRequest('cancel-purchase', ['purchase_id' => $id]);

        $success = $data['success'];
        $this->log('[loyalty] '.$data);
        if ($success == False) {
            Yii::error($data['error_description']);
        }
        return $data;
    }

    // send-register-code request   
    public function sendRegisterCode($phone)
    {
        $phone = $this->getClearedPhone($phone);
        $data = $this->sendRequest('send-register-code', ['phone' => $phone]);

        $success = $data['success'];
        $this->log($success.''.$phone);
        if ($success == False) {
            Yii::error($data['error_description']);
        }
        return $success;
    }

    // send-write-off-confirmation-code request
    public function sendWriteOffConfirmationCode($phone)
    {
        $phone = $this->getClearedPhone($phone);
        $data = $this->sendRequest('send-write-off-confirmation-code', ['phone' => $phone]);

        $success = $data['success'];
        $this->log($success.''.$phone);
        if ($success == False) {
            Yii::error($data['error_description']);
        }
        return $success;
    }

    // verify-confirmation-code request
    public function verifyConfirmationCode($phone, $code)
    {
        $phone = $this->getClearedPhone($phone);
        $data = $this
                ->sendRequest('verify-confirmation-code', 
                [
                    'phone' => $phone,
                    'code' => $code
                ]);

        $success = $data['success'];
        $this->log($success.''.$code);
        if ($success == False) {
            Yii::error($data['error_description']);
        }
        return $success;
    }
}
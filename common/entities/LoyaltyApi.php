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

    public function getHttpClient()
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

    // buyer-info-detail request
    public function getInfo($phone)
    {
        $phone = $this->getClearedPhone($phone);
        $response = $this
                    ->getHttpClient()
                    ->setUrl('buyer-info-detail')
                    ->setData([
                        'identificator' => $phone
                    ])
                    ->send();

        $data = $response->data;
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
        $response = $this
                    ->getHttpClient()
                    ->setUrl('buyer-register')
                    ->setData([
                        'phone' => $phone,
                        'name' => $user->username,
                        'email' => $user->email
                    ])
                    ->send();
        $data = $response->data;
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
        throw new NotFoundHttpException('Запрошенная вами страница не существует.');
    }

    // write-off-request request
    public function getWriteOff($id)
    {
        throw new NotFoundHttpException('Запрошенная вами страница не существует.');
    }

    // purchase request
    public function purchase($id)
    {
        throw new NotFoundHttpException('Запрошенная вами страница не существует.');
    }

    // change-purchase-status request
    public function changePurchaseStatus($purchase_id, $external_purchase_id, $status)
    {
        throw new NotFoundHttpException('Запрошенная вами страница не существует.');
    }

    // edit-purchase request
    public function editPurchase($order)
    {
        throw new NotFoundHttpException('Запрошенная вами страница не существует.');
    }

    // cancel-purchase request
    public function cancelPurchase($order)
    {
        throw new NotFoundHttpException('Запрошенная вами страница не существует.');
    }

    // send-register-code request   
    public function sendRegisterCode($phone)
    {
        $phone = $this->getClearedPhone($phone);
        $response = $this
                ->getHttpClient()
                ->setUrl('send-register-code')
                ->setData([
                    'phone' => $phone
                ])
                ->send();

        $data = $response->data;
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
        throw new NotFoundHttpException('Запрошенная вами страница не существует.');
    }

    // verify-confirmation-code request
    public function verifyConfirmationCode($phone, $code)
    {
        $phone = $this->getClearedPhone($phone);
        $response = $this
                ->getHttpClient()
                ->setUrl('verify-confirmation-code')
                ->setData([
                    'phone' => $phone,
                    'code' => $code
                ])
                ->send();
        $data = $response->data;
        $success = $data['success'];
        $this->log($success.''.$code);
        if ($success == False) {
            Yii::error($data['error_description']);
        }
        return $success;
    }
}
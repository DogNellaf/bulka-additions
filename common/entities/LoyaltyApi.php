<?php

namespace common\entities;

use yii;
use yii\base\Component;
use yii\httpclient\Client;

class LoyaltyApi extends Component
{
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

    // buyer-info request
    public function getInfo($phone)
    {
        $response = $this->getHttpClient()
                    ->setUrl('buyer-info')
                    ->setData([
                        'identificator' => $phone
                    ])
                    ->send();

        if ($response->isOk) {
            $data = $data;
            $success = $response->data['success'];
            if ($success == True) {
                $buyerDTO = new BuyerInfo();
                $buyerDTO->$identificator_type = ['identificator_type'];
                $buyerDTO->$is_register = $data['is_register'];
                $buyerDTO->$blocked = $data['blocked'];
                $buyerDTO->$phone = $data['phone'];
                $buyerDTO->$name = $data['name'];
                $buyerDTO->$gender = $data['gender'];
                $buyerDTO->$birth_date = $data['birth_date'];
                $buyerDTO->$email = $data['email'];
                $buyerDTO->$groip_id = $data['groip_id'];
                $buyerDTO->$group_name = $data['group_name'];
                $buyerDTO->$balance = $data['balance'];
                $buyerDTO->$balance_bonus_accumulated = $data['balance_bonus_accumulated'];
                $buyerDTO->$balance_bonus_present = $data['balance_bonus_present'];
                $buyerDTO->$balance_bonus_action = $data['balance_bonus_action'];
                $buyerDTO->$bonus_inactive = $data['bonus_inactive'];
                $buyerDTO->$bonus_next_activation_text = $data['bonus_next_activation_text'];
                $buyerDTO->$write_off_confirmation_required = $data['write_off_confirmation_required'];
                $buyerDTO->$registration_confirmation_required = $data['registration_confirmation_required'];
                $buyerDTO->$is_allowed_change_card = $data['is_allowed_change_card'];
                $buyerDTO->$phone_checked = $data['phone_checked'];
                $buyerDTO->$additional_info = $data['additional_info'];
                $buyerDTO->$is_refused_receive_messages = $data['is_refused_receive_messages'];
                return $buyerDTO;
            }
        }
    }

    // buyer-info-detail request
    public function getInfoDetail($phone)
    {
        throw new NotFoundHttpException('Запрошенная вами страница не существует.');
    }

    // buyer-register request
    public function register($user)
    {
        $response = $this->getHttpClient()
                    ->setUrl('buyer-info')
                    ->setData([
                        'phone' => $user->phone,
                        'name' => $user->name,
                        'email' => $user->email
                    ])
                    ->send();

        if ($response->isOk) {
            $data = $data;
            return $response->data['success'];
        }
        return false;
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

    // send-register-info request   
    public function sendRegisterCode($phone)
    {
        throw new NotFoundHttpException('Запрошенная вами страница не существует.');
    }

    // send-write-off-confirmation-code request
    public function sendWriteOffConfirmationCode($phone)
    {
        throw new NotFoundHttpException('Запрошенная вами страница не существует.');
    }

    // verify-confirmation-code request
    public function verifyConfirmationCode($phone, $code)
    {
        throw new NotFoundHttpException('Запрошенная вами страница не существует.');
    }
}
<?php

namespace backend\controllers;

use common\entities\OrderItems;
use Yii;
use common\entities\Orders;
use backend\models\OrdersSearch;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class OrdersController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    // buyer-info request
    public function getInfo($phone)
    {
        // if (($model = User::findOne($id)) !== null) {
        //     return $model;
        // }
        throw new NotFoundHttpException('Запрошенная вами страница не существует.');
    }

    // buyer-info-detail request
    public function getInfoDetail($phone)
    {
        // if (($model = Orders::findOne($id)) !== null) {
        //     return $model;
        // }
        throw new NotFoundHttpException('Запрошенная вами страница не существует.');
    }

    // buyer-register request
    public function register($user)
    {
        // $model = $this->findModel($id);
        // $model->status = $model->status ? 0 : 1;
        // $model->save();
        // return $this->redirect(Yii::$app->request->referrer);
        throw new NotFoundHttpException('Запрошенная вами страница не существует.');
    }

    // buyer-edit request
    public function editBuyer($user)
    {
        // $model = $this->findModel($id);
        // $model->formXml();
        // $model->xml_formed = 1;
        // $model->save();
        // return $this->redirect(Yii::$app->request->referrer);
        throw new NotFoundHttpException('Запрошенная вами страница не существует.');
    }

    // write-off-request request
    public function getWriteOff($id)
    {
        // $model = $this->findModel($id);
        // $model->sendSMS();
        // return $this->redirect(Yii::$app->request->referrer);
        throw new NotFoundHttpException('Запрошенная вами страница не существует.');
    }

    // purchase request
    public function purchase($id)
    {
        // $model = $this->findModel($id);
        // try {
        //     $model->mail();
        //     $model->email_result = 'OK';
        // } catch (\RuntimeException $e) {
        //     $model->email_result = $e->getMessage();
        // }
        // $model->save();
        // return $this->redirect(Yii::$app->request->referrer);
        throw new NotFoundHttpException('Запрошенная вами страница не существует.');
    }

    // change-purchase-status request
    public function changePurchaseStatus($purchase_id, $external_purchase_id, $status)
    {
        // $model = $this->findModel($id);
        // try {
        //     $model->mail();
        //     $model->email_result = 'OK';
        // } catch (\RuntimeException $e) {
        //     $model->email_result = $e->getMessage();
        // }
        // $model->save();
        // return $this->redirect(Yii::$app->request->referrer);
        throw new NotFoundHttpException('Запрошенная вами страница не существует.');
    }

    // edit-purchase request
    public function editPurchase($order)
    {
        // $model = $this->findModel($id);
        // try {
        //     $model->mail();
        //     $model->email_result = 'OK';
        // } catch (\RuntimeException $e) {
        //     $model->email_result = $e->getMessage();
        // }
        // $model->save();
        // return $this->redirect(Yii::$app->request->referrer);
        throw new NotFoundHttpException('Запрошенная вами страница не существует.');
    }

    // cancel-purchase request
    public function cancelPurchase($order)
    {
        // $model = $this->findModel($id);
        // try {
        //     $model->mail();
        //     $model->email_result = 'OK';
        // } catch (\RuntimeException $e) {
        //     $model->email_result = $e->getMessage();
        // }
        // $model->save();
        // return $this->redirect(Yii::$app->request->referrer);
        throw new NotFoundHttpException('Запрошенная вами страница не существует.');
    }

    // send-register-info request   
    public function sendRegisterCode($phone)
    {
        // $model = $this->findModel($id);
        // try {
        //     $model->mail();
        //     $model->email_result = 'OK';
        // } catch (\RuntimeException $e) {
        //     $model->email_result = $e->getMessage();
        // }
        // $model->save();
        // return $this->redirect(Yii::$app->request->referrer);
        throw new NotFoundHttpException('Запрошенная вами страница не существует.');
    }

    // send-write-off-confirmation-code request
    public function sendWriteOffConfirmationCode($phone)
    {
        // $model = $this->findModel($id);
        // try {
        //     $model->mail();
        //     $model->email_result = 'OK';
        // } catch (\RuntimeException $e) {
        //     $model->email_result = $e->getMessage();
        // }
        // $model->save();
        // return $this->redirect(Yii::$app->request->referrer);
        throw new NotFoundHttpException('Запрошенная вами страница не существует.');
    }

    // verify-confirmation-code request
    public function verifyConfirmationCode($phone, $code)
    {
        // $model = $this->findModel($id);
        // try {
        //     $model->mail();
        //     $model->email_result = 'OK';
        // } catch (\RuntimeException $e) {
        //     $model->email_result = $e->getMessage();
        // }
        // $model->save();
        // return $this->redirect(Yii::$app->request->referrer);
        throw new NotFoundHttpException('Запрошенная вами страница не существует.');
    }
}

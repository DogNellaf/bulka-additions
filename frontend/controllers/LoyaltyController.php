<?php

namespace frontend\controllers;

use common\entities\Orders;
use common\entities\UserAddress;
use common\entities\LoyaltyApi;
use Yii;
use frontend\components\FrontendController;
use frontend\forms\LoyaltyConfirmForm;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;

class LoyaltyController extends FrontendController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['signup', 'request-password-reset', 'reset-password'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionRegister()
    {
        $this->setMeta('Регистрация в бонусной системе');

        $model = new LoyaltyConfirmForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()){

                // TODO

                if ($model->editAccount()) {
                    Yii::$app->session->setFlash('success', 'Изменения приняты.');
                } else {
                    Yii::$app->session->setFlash('error', 'Произошла ошибка.');
                }
                return $this->refresh();
            }
        }
        return $this->render('confirm', [
            'model' => $model
        ]);
    }

    public function actionConfirm()
    {
        $loyalty = new LoyaltyApi();
        $user = Yii::$app->user->identity;
        $buyer = $loyalty->getInfo($user->phone);

        if ($buyer->is_registered == False) {
            $loyalty->register($user);
        }
        
        return $this->redirect('account/');
    }

    public function actionWallet($id)
    {
        $this->setMeta('Кошелек');

        return $this->renderAjax('wallet');
    }

    public function actionAbout()
    {
        $this->setMeta('О программе лояльности');

        return $this->renderAjax('about');
    }
}
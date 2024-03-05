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
        $loyalty = new LoyaltyApi();
        $user = Yii::$app->user->identity;
        $code = $loyalty->sendRegisterCode($user->phone);

        return $this->render('register', [
            'model' => $model
        ]);
    }

    public function actionConfirm()
    {
        $loyalty = new LoyaltyApi();
        $user = Yii::$app->user->identity;

        $request = Yii::$app->request;
        $num1 = $request->get('num1');
        $num2 = $request->get('num2');
        $num3 = $request->get('num3');
        // $num4 = $request->get('num4');
        $code = $num1.$num2.$num3.$num4;

        $result = $loyalty->verifyConfirmationCode($user->phone, $code);

        if ($result == False) {
            return $this->redirect('/loyalty/register');
        }
        
        return $this->goHome();
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
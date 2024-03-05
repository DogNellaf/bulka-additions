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
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                    'confirm' => ['post']
                ],
            ],
        ];
    }

    public function actionRegister()
    {
        $this->setMeta('Регистрация в бонусной системе');

        $loyalty = new LoyaltyApi();
        $user = Yii::$app->user->identity;

        $is_registered = $loyalty->getInfo($user->phone)->is_registered;

        if ($is_registered == True) {
            throw new ForbiddenHttpException('Вы уже были зарегистрированы');
        }

        $result = $loyalty->register($user);
        Yii::info('ababa'.Json::encode($result));

        $code = $loyalty->sendRegisterCode($user->phone);

        return $this->render('register');
    }

    public function actionConfirm()
    {
        $loyalty = new LoyaltyApi();
        $user = Yii::$app->user->identity;

        $is_registered = $loyalty->getInfo($user->phone)->is_registered;

        if ($is_registered == True) {
            throw new ForbiddenHttpException('Вы уже были зарегистрированы');
        }

        $request = Yii::$app->request;
        $num1 = $request->post('num1');
        $num2 = $request->post('num2');
        $num3 = $request->post('num3');
        $code = $num1.$num2.$num3;

        $result = $loyalty->verifyConfirmationCode($user->phone, $code);

        if ($result == False) {
            return $this->redirect('/loyalty/register');
        }

        return $this->redirect('/account/');
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
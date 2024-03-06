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
use yii\web\ForbiddenHttpException;

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

    protected function checkRegistered($phone) {
        $loyalty = new LoyaltyApi();
        return $loyalty->getInfo($phone)['is_registered'];
    }

    public function actionRegister()
    {
        $this->setMeta('Регистрация в бонусной системе');

        $loyalty = new LoyaltyApi();
        $user = Yii::$app->user->identity;

        if ($this->checkRegistered($user->phone) == True) {
            Yii::$app->session->setFlash('error', 'Вы уже были зарегистрированы'); //$this->cart->getBonuses()
            return $this->redirect(Yii::$app->request->referrer);
        }

        if ($user->phone == null) {
            Yii::$app->session->setFlash('error', 'Необходимо указать номер телефона'); //$this->cart->getBonuses()
            return $this->redirect(Yii::$app->request->referrer);
        }

        $loyalty->register($user);
        $code = $loyalty->sendRegisterCode($user->phone);

        return $this->render('register');
    }

    public function actionConfirm()
    {
        $loyalty = new LoyaltyApi();
        $user = Yii::$app->user->identity;

        if ($this->checkRegistered($user->phone) == True) {
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
}
<?php

namespace frontend\controllers;

use common\entities\Orders;
use common\entities\UserAddress;
use common\entities\LoyaltyApi;
use frontend\forms\PasswordResetRequestForm;
use frontend\forms\ResetPasswordForm;
use frontend\forms\SignupForm;
use Yii;
use frontend\components\FrontendController;
use frontend\forms\AccountForm;
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

        $model = new LoyaltyRegisterForm();
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
        return $this->render('register', [
            'model' => $model
        ]);
    }

    public function actionConfirm()
    {
        $this->setMeta('Код подтверждения');

        $user = Yii::$app->user->identity;
        $model = new UserAddress();
        $model->user_id = $user->getId();
        if ($model->load(Yii::$app->request->post())) {
            if ($address = UserAddress::findOne([
                'user_id' => $model->user_id,
                'value' => $model->value,
                'street' => $model->street,
                'house' => $model->house,
                'apartment' => $model->apartment,
                'floor' => $model->floor,
                'entrance' => $model->entrance,
                'intercom' => $model->intercom,
                'note' => $model->note
            ])) {
                Yii::$app->session->setFlash('error', 'Такой адрес уже сохранен.');
                return $this->redirect(['index']);
            }
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Изменения приняты.');
                return $this->redirect(['index']);
            }
        }
        return $this->redirect()
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
<?php

namespace frontend\controllers;

use common\entities\Orders;
use common\entities\UserAddress;
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

class AccountController extends FrontendController
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

    public function actionIndex()
    {
        $this->setMeta('Личный кабинет');

        $model = new AccountForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()){
                if ($model->editAccount()) {
                    Yii::$app->session->setFlash('success', 'Изменения приняты.');
                } else {
                    Yii::$app->session->setFlash('error', 'Произошла ошибка.');
                }
                return $this->refresh();
            }
        }
        return $this->render('account', [
            'model' => $model,
        ]);
    }

    public function actionAddAddress()
    {
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
        return $this->renderAjax('address', [
            'model' => $model,
        ]);
    }

    public function actionRemoveAddress($id)
    {
        if ($userAddress = UserAddress::findOne($id)) {
            $userAddress->delete();
            Yii::$app->session->setFlash('success', 'Изменения приняты.');
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->redirect(['/account/index']);
                }
            }
        }
        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Проверьте свою почту и следуйте инструкциям.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Простите, нам не удалось сбросить пароль для указанного e-mail адреса.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (\DomainException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Новый пароль сохранен.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionClearHistory()
    {
        /* @var $user \common\entities\User */
        $user = Yii::$app->user->identity;
        foreach ($user->orders as $order) {
            if ($order->user_status) {
                $order->user_status = 0;
                $order->save();
            }
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionOrderView($id)
    {
        $order = Orders::findOne($id);
        $cart = Json::decode($order->cart_json, true);
        return $this->renderAjax('order_view', [
            'cart' => $cart,
            'sum' => $order->cost,
            'id' => $id,
        ]);
    }

}
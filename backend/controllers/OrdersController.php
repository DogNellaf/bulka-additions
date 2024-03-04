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
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
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

    public function actionIndex()
    {
        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = ['defaultOrder' => ['created_at' => SORT_DESC]];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        $dataProvider = new ActiveDataProvider([
            'query' => OrderItems::find()->andWhere(['order_id' => $model->id]),
        ]);

        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new Orders();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->user_status == 1) {
            Yii::$app->session->setFlash('error', 'Невозможно удалиь заказ, который отображается в профиле пользователя.');
            return $this->redirect(Yii::$app->request->referrer);
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Orders::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Запрошенная вами страница не существует.');
    }

    public function actionStatus($id)
    {
        $model = $this->findModel($id);
        $model->status = $model->status ? 0 : 1;
        $model->save();
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionFormXml($id)
    {
        $model = $this->findModel($id);
        $model->formXml();
        $model->xml_formed = 1;
        $model->save();
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionSendSms($id)
    {
        $model = $this->findModel($id);
        $model->sendSMS();
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionSendEmail($id)
    {
        $model = $this->findModel($id);
        try {
            $model->mail();
            $model->email_result = 'OK';
        } catch (\RuntimeException $e) {
            $model->email_result = $e->getMessage();
        }
        $model->save();
        return $this->redirect(Yii::$app->request->referrer);
    }
}

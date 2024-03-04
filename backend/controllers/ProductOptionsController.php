<?php

namespace backend\controllers;

use Yii;
use common\entities\ProductOptions;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\entities\Products;

/**
 * ProductOptionsController implements the CRUD actions for ProductOptions model.
 */
class ProductOptionsController extends Controller
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

    public function actionIndex($id)
    {
        if (!$category = Products::findOne(['id' => $id])){
            throw new NotFoundHttpException('Запрошенная вами страница не существует.');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => ProductOptions::find()->andWhere(['product_id' => $category->id]),
            'sort' => ['defaultOrder' => ['sort' => SORT_ASC]],
            'pagination' => false
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'category' => $category,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate($id)
    {
        if (!$category = Products::findOne(['id' => $id])){
            throw new NotFoundHttpException('Запрошенная вами страница не существует.');
        }
        $model = new ProductOptions();
        $model->product_id = $category->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $id]);
        }

        return $this->render('create', [
            'model' => $model,
            'category' => $category,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->product_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();

        return $this->redirect(['index', 'id' => $model->product_id]);
    }

    protected function findModel($id)
    {
        if (($model = ProductOptions::findOne($id)) !== null) {
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
}

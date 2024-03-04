<?php

namespace backend\controllers;

use Yii;
use common\entities\ProductWeights;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\entities\Products;

/**
 * ProductWeightsController implements the CRUD actions for ProductWeights model.
 */
class ProductWeightsController extends Controller
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

    public function actionIndex($slug)
    {
        if (!$category = Products::findOne(['slug' => $slug])){
            throw new NotFoundHttpException('Запрошенная вами страница не существует.');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => ProductWeights::find()->andWhere(['product_id' => $category->id]),
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

    public function actionCreate($slug)
    {
        if (!$category = Products::findOne(['slug' => $slug])){
            throw new NotFoundHttpException('Запрошенная вами страница не существует.');
        }
        $model = new ProductWeights();
        $model->product_id = $category->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['index', 'slug' => $slug]);
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
            return $this->redirect(['index', 'slug' => $model->product->slug]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();

        return $this->redirect(['index', 'slug' => $model->product->slug]);
    }

    protected function findModel($id)
    {
        if (($model = ProductWeights::findOne($id)) !== null) {
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

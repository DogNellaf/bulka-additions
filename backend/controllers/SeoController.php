<?php

namespace backend\controllers;

use Yii;
use common\entities\Seo;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * SeoController implements the CRUD actions for Seo model.
 */
class SeoController extends Controller
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
        $dataProvider = new ActiveDataProvider([
            'query' => Seo::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($page)
    {
        $model = $this->findModel($page);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionCreate()
    {
        $model = new Seo();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'page' => $model->page]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($page)
    {
        $model = $this->findModel($page);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'page' => $model->page]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    protected function findModel($page)
    {
        if (!$model = Seo::findOne(['page' => $page])) {
            throw new NotFoundHttpException('Запрошенная вами страница не существует.');
        }
        return $model;
    }
}

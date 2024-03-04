<?php

namespace backend\controllers;

use common\entities\BreakfastCategories;
use common\entities\MenuIcons;
use common\entities\BreakfastProducts;
use Yii;
use common\entities\Products;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use common\entities\ProductCategories;
use yii\db\Query;

/**
 * BreakfastProductsController implements the CRUD actions for BreakfastProducts model.
 */
class BreakfastProductsController extends Controller
{
    /**
     * @inheritdoc
     */
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
        if (!$category = BreakfastCategories::findOne(['slug' => $slug])) {
            throw new NotFoundHttpException('Запрошенная вами страница не существует.');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => BreakfastProducts::find()->andWhere(['category_id' => $category->id]),
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
        if (!$category = BreakfastCategories::findOne(['slug' => $slug])) {
            throw new NotFoundHttpException('Запрошенная вами страница не существует.');
        }

        $title_icon = Yii::$app->request->post()['title_icon'];
        $desc_icon = Yii::$app->request->post()['desc_icon'];
        $link_icon = Yii::$app->request->post()['link_icon'];

        $model = new BreakfastProducts();
        $model->category_id = $category->id;
        $model->title_icon = $title_icon;
        $model->desc_icon = $desc_icon;
        $model->link_icon = $link_icon;

        if ($model->load(Yii::$app->request->post())) {
            //$model->rel_products = Json::encode($model->rel_products);
            if ($model->save()) {
                return $this->redirect(['index', 'slug' => $slug]);
            }
        }
        $icons = MenuIcons::find()->all();


        return $this->render('create', [
            'model' => $model,
            'category' => $category,
            'icons' => $icons,
        ]);
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $title_icon = Yii::$app->request->post()['title_icon'];
        $desc_icon = Yii::$app->request->post()['desc_icon'];
        $link_icon = Yii::$app->request->post()['link_icon'];

        if ($model->load(Yii::$app->request->post())) {
            $model->category_id = intval(Yii::$app->request->post()["BreakfastProducts"]["category_id"]);
            $model->title_icon = $title_icon;
            $model->desc_icon = $desc_icon;
            $model->link_icon = $link_icon;
            if ($model->save()) {
                return $this->redirect(['index', 'slug' => $model->category->slug]);
            }
        }

        $icons = MenuIcons::find()->all();

        return $this->render('update', [
            'model' => $model,
            'icons' => $icons,
        ]);
    }


    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();

        return $this->redirect(['index', 'slug' => $model->category->slug]);
    }

    protected function findModel($id)
    {
        if (($model = BreakfastProducts::findOne($id)) !== null) {
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

    public function actionProductsSort()
    {
        $model = $this->findModel(\Yii::$app->request->post('id'));
        $data = Json::decode(\Yii::$app->request->post('sort'));

        $arr = [];
        foreach ($data[0] as $item) {
            $arr[] = $item["id"];
        }

        $model->categories = Json::encode($arr);
        $model->save();

        return 'ok';
    }

    public function actionList($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query;
            $query->select('id, title AS text')
                ->from('blk_products')
                ->where(['like', 'title', $q])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => BreakfastProducts::find($id)->title];
        }
        return $out;
    }
}

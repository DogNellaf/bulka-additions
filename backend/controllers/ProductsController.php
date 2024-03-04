<?php

namespace backend\controllers;

use Yii;
use common\entities\Products;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use common\entities\ProductCategories;
use yii\db\Query;

/**
 * ProductsController implements the CRUD actions for Products model.
 */
class ProductsController extends Controller
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
        if (!$category = ProductCategories::findOne(['slug' => $slug])){
            throw new NotFoundHttpException('Запрошенная вами страница не существует.');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Products::find()->andWhere(['category_id' => $category->id]),
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
        if (!$category = ProductCategories::findOne(['slug' => $slug])){
            throw new NotFoundHttpException('Запрошенная вами страница не существует.');
        }
        $model = new Products();
        $model->category_id = $category->id;

        if ($model->load(Yii::$app->request->post())) {
            //$model->rel_products = Json::encode($model->rel_products);
            if ($model->save()) {
                return $this->redirect(['index', 'slug' => $slug]);
            }
        }

        //$model->rel_products = ArrayHelper::toArray(Json::decode($model->rel_products));

        return $this->render('create', [
            'model' => $model,
            'category' => $category,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->category_id = intval(Yii::$app->request->post()["Products"]["category_id"]);
            //$model->save();
            $sbmt = \Yii::$app->request->post('sbmt');
            if (strpos($sbmt, 'deladdit_') === 0) {
                $itemId = str_replace('deladdit_','',$sbmt);
                $array = ArrayHelper::toArray(Json::decode($model->rel_products));
                if(($key = array_search($itemId,$array)) !== FALSE){
                    unset($array[$key]);
                }
                $model->rel_products = Json::encode($array);
                $model->save();

                return $this->render('update', [
                    'model' => $model,
                ]);
            }
            if ($sbmt =='additem'){
                $itemId = \Yii::$app->request->post('additem');

                if ($itemId) {
                    $array = ArrayHelper::toArray(Json::decode($model->rel_products));
                    if (!in_array($itemId,$array)) {
                        $array[] = intval($itemId);
                        $model->rel_products = Json::encode($array);
                    }
                }
                $model->save();

                return $this->render('update', [
                    'model' => $model,
                ]);
            }

            if ($model->save()) {
                return $this->redirect(['index', 'slug' => $model->category->slug]);
            }
        }

        return $this->render('update', [
            'model' => $model,
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
        if (($model = Products::findOne($id)) !== null) {
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
        $data  = Json::decode(\Yii::$app->request->post('sort'));

        $arr = [];
        foreach($data[0] as $item){
            $arr[] = $item["id"];
        }

        $model->categories = Json::encode($arr);
        $model->save();

        return 'ok';
    }

    public function actionList($q = null, $id = null) {
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
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Products::find($id)->title];
        }
        return $out;
    }
}

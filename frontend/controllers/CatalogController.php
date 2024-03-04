<?php

namespace frontend\controllers;

use common\entities\Products;
use common\entities\ProductCategories;
use common\entities\ProductSections;
use common\entities\UserFavorites;
use frontend\components\FrontendController;
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class CatalogController extends FrontendController
{
    public function actionIndex($slug = null)
    {
        $sections = ProductSections::getDb()->cache(function () {
            return ProductSections::find()->having(['status' => 1])->orderBy(['sort' => SORT_ASC])->all();
        }, Yii::$app->params['cacheTime']);

        if ($slug) {
            if (!$category = ProductCategories::findOne(['slug' => $slug])) {
                throw new NotFoundHttpException('Запрошенная вами страница не существует.');
            }
            $this->setMeta($category->title);
            $products = Products::getDb()->cache(function () use ($category) {
                return Products::find()->having(['category_status' => 1, 'status' => 1])->andWhere(['category_id' => $category->id])->orderBy(['sort' => SORT_ASC])->all();
            }, Yii::$app->params['cacheTime']);
        } else {
            $category = null;
            $categories = ProductCategories::getDb()->cache(function () use ($category) {
                return ProductCategories::find()->having(['status' => 1])->orderBy(['sort' => SORT_ASC])->all();
            }, Yii::$app->params['cacheTime']);
            $productsSort = ArrayHelper::getColumn($categories, 'id');
            $products = Products::getDb()->cache(function () use ($productsSort) {
                return Products::find()->having(['category_status' => 1, 'status' => 1])
                    ->orderBy([new Expression(sprintf("FIELD(category_id, %s)", implode(",", $productsSort)))])
                    ->addOrderBy(['sort' => SORT_ASC])
                    ->all();
            }, Yii::$app->params['cacheTime']);
        }

        return $this->render('index', [
            'sections' => $sections,
            'products' => $products,
            'category' => $category,
        ]);
    }

    public function actionProduct($slug)
    {
        /* @var $item Products */
        if (!$product = Products::findOne(['slug' => $slug])) {
            throw new NotFoundHttpException('Запрошенная вами страница не существует.');
        }

        /*
        $relProducts = Products::find()
            ->having(['category_id' => $product->category_id, 'status' => 1])
            ->andWhere(['not', ['slug' => $slug]])
            ->orderBy('sort')
            ->limit(4)
            ->all();
        */

        return $this->render('product', [
            'product' => $product,
            //'relProducts' => $relProducts,
        ]);
    }

    public function actionFavoriteAdd($id)
    {
        if (!Yii::$app->user->isGuest) {
            $fav = UserFavorites::find()->where(['user_id'=>Yii::$app->user->id,'product_id'=>$id])->one();
            if ($fav==null){
                $fav = new UserFavorites();
                $fav->user_id = Yii::$app->user->id;
                $fav->product_id = $id;
                $fav->save();
            }
        }
        return '1';
    }

    public function actionFavoriteDel($id)
    {
        if (!Yii::$app->user->isGuest) {
            $fav = UserFavorites::find()->where(['user_id'=>Yii::$app->user->id,'product_id'=>$id])->one();
            if ($fav!=null){
                $fav->delete();
            }
        }
        return '0';
    }

}
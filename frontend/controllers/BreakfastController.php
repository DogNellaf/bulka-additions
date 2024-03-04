<?php
namespace frontend\controllers;


use common\entities\Chef;
use common\entities\BreakfastCategories;
use Yii;
use frontend\components\FrontendController;
use yii\helpers\VarDumper;

class BreakfastController extends FrontendController
{
    public function actionIndex($lang = 'ru'): string
    {
        $categories = BreakfastCategories::find()->andWhere(['status' => 1])->orderBy('sort')->all();
        $chefs = Chef::find()->andWhere(['status' => 1])->orderBy('sort')->all();

        $this->layout = false;

        return $this->render($lang . '/index', [
            'categories' => $categories,
            'chefs' => $chefs,
        ]);
    }

}

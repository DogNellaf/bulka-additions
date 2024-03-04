<?php
namespace frontend\controllers;


use common\entities\Chef;
use common\entities\MenuCategories;
use Yii;
use frontend\components\FrontendController;
use yii\helpers\VarDumper;

class MenuController extends FrontendController
{
    public function actionIndex($lang = 'ru'): string
    {
        $categories = MenuCategories::find()->andWhere(['status' => 1])->orderBy('sort')->all();
        $chefs = Chef::find()->andWhere(['status' => 1])->orderBy('sort')->all();

        $this->layout = false;

        return $this->render($lang . '/index', [
            'categories' => $categories,
            'chefs' => $chefs,
        ]);
    }

}

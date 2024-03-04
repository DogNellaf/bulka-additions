<?php

namespace frontend\components;

use common\entities\Seo;
use yii\web\Controller;

class FrontendController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'view' => '@frontend/views/common/error.php'
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    protected function findSeoAndSetMeta($page)
    {
        /* @var $seo Seo */
        $seo = Seo::getDb()->cache(function () use ($page) {
            return Seo::findOne(['page' => $page]);
        }, \Yii::$app->params['cacheTime']);
        $this->setMeta($seo->meta_title ?: $page, $seo->meta_description, $seo->meta_keywords);
    }

    protected function setMeta($title = null, $description = null, $keywords = null)
    {
        $this->view->title = $title;
        $this->view->registerMetaTag(['name' => 'keywords', 'content' => "$keywords"]);
        $this->view->registerMetaTag(['name' => 'description', 'content' => "$description"]);
    }
}
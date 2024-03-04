<?php

namespace frontend\assets;

use Yii;
use yii\web\AssetBundle;

class CheckoutYandexMapAsset extends AssetBundle
{
    public $sourcePath = '@webroot/js';
    public $js = [
        'https://api-maps.yandex.ru/2.1/?apikey=',
        'checkout_yandex_map.js?v=1',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];

    public function init()
    {
        parent::init();
        $this->js[0] .= Yii::$app->params['apiKeyYandexMap'].'&lang=ru_RU';
    }
}
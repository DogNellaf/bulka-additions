<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'https://fonts.googleapis.com/css?family=Podkova:400,500,600,700,800',
        'css/site.css',
    ];
    public $js = [
        'js/script.js',
        'js/app.js'
    ];
    public $depends = [
        'common\assets\GridAsset',
        'dmstr\web\AdminLteAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}

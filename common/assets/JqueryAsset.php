<?php

namespace common\assets;

use yii\web\AssetBundle;

class JqueryAsset extends AssetBundle
{
    public $sourcePath = '@common/js';
    public $js = [
        'jquery.js',
        'bootstrap.min.js',
    ];
}

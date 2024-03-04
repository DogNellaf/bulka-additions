<?php

namespace common\assets;

use yii\web\AssetBundle;

class GridAsset extends AssetBundle
{
    public $sourcePath = '@common/css';
    public $css = [
        'bootstrap-reboot.css',
        'bootstrap-grid.css',
    ];
}
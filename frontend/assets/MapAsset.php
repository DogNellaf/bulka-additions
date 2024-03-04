<?php
namespace frontend\assets;
use yii\web\AssetBundle;

class MapAsset extends AssetBundle
{
    public $sourcePath = '@webroot/js';
    public $js = [
        'https://maps.googleapis.com/maps/api/js?key=AIzaSyAWStd-NyLSZysJWvnRQhsM9cElyTw9wZo',
        'map.js?v=4',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
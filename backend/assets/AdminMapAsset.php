<?php
namespace backend\assets;
use yii\web\AssetBundle;

class AdminMapAsset extends AssetBundle
{
    public $sourcePath = '@webroot/js';
    public $js = [
        'https://maps.googleapis.com/maps/api/js?key=AIzaSyAWStd-NyLSZysJWvnRQhsM9cElyTw9wZo&libraries=drawing',
        '/js/admin_map.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
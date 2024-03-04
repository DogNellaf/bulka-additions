<?php
namespace frontend\assets;
use yii\web\AssetBundle;

class CheckoutMapAsset extends AssetBundle
{
    public $sourcePath = '@webroot/js';
    public $js = [
        'https://maps.googleapis.com/maps/api/js?key=AIzaSyAWStd-NyLSZysJWvnRQhsM9cElyTw9wZo',
        'checkout_map.js?v=4',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
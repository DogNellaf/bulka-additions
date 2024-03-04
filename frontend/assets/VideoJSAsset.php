<?php
namespace frontend\assets;
use yii\web\AssetBundle;

class VideoJSAsset extends AssetBundle
{
    public $sourcePath = '@webroot/css';
    public $css = [
        'https://vjs.zencdn.net/7.18.1/video-js.css',
        'video.css'
    ];
    public $js = [
        'https://vjs.zencdn.net/7.18.1/video.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
}
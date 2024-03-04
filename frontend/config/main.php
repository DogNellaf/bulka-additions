<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'language' => $params['defaultLanguage'],
    'id' => 'app-frontend',
    'basePath' => realpath(__DIR__ . '/../'),
    'bootstrap' => [
        'log',
        'common\bootstrap\SetUp',
        'devicedetect'
    ],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\entities\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            //debug mode
            //'flushInterval' => 1,
            'flushInterval' => 100,
            'targets' => [
                /*
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                */
                //debug mode
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'trace', 'info'],
                    'exportInterval' => 100,
                    //'exportInterval' => 1,
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
//            'class' => 'codemix\localeurls\UrlManager',
//            'languages' => [$params['defaultLanguage'], 'en'],
//            'enableDefaultLanguageUrlCode' => true,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => 'site/index',
                'catalog/product/<slug:(\w|-)+>' => 'catalog/product',
                'catalog/section/<section:(\w|-)+>' => 'catalog/index',
                'catalog/<slug:(\w|-)+>' => 'catalog/index',
                'catalog' => 'catalog/index',
                'restaurant/<slug:(\w|-)+>' => 'site/restaurant',
                'restaurants' => 'site/restaurants',
                'gallery/<slug:(\w|-)+>' => 'site/gallery',
                'galleries' => 'site/galleries',
                'story/<slug:(\w|-)+>' => 'site/story',
                'stories' => 'site/stories',
                'contacts' => 'site/contacts',
                'search/<str:(\w|-)+>' => 'site/search',
                'search' => 'site/search',
                'policy' => 'site/policy',
                'delivery-terms' => 'site/delivery-terms',
                'menu/<lang:(ru|en)+>' => 'menu/index',
                'breakfast/<lang:(ru|en)+>' => 'breakfast/index',
            ],
        ],
        'devicedetect' => [
            'class' => 'alexandernst\devicedetect\DeviceDetect'
        ],
    ],
    'params' => $params,
];

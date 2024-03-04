<?php

namespace common\bootstrap;

use common\models\Cart;
use common\models\storage\HybridStorage;
use yii\base\BootstrapInterface;
use yii\base\ErrorHandler;
use yii\caching\Cache;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = \Yii::$container;

        $container->setSingleton('yii\mail\MailerInterface', function () use ($app) {
            return $app->mailer;
        });

        $container->setSingleton(ErrorHandler::class, function () use ($app) {
            return $app->errorHandler;
        });

        $container->setSingleton(Cache::class, function () use ($app) {
            return $app->cache;
        });

        $container->setSingleton('common\models\Cart', function () use ($app) {
            return new Cart(
                new HybridStorage(\Yii::$app->user, 'cart', 3600 * 24, $app->db)
            );
        });
    }
}
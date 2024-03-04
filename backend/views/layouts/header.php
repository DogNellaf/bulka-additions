<?php

use yii\helpers\Html;
use backend\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */
/* @var $username string */
/* @var $user \common\entities\User */

$user = Yii::$app->user->identity;
$bundle = AppAsset::register($this);
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini"><img src="/favicon.ico"></span><span class="logo-lg">' . Yii::$app->name . '</span>', '/', ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= $user->userProfile->getAvatar('/images/anonymous.jpg') ?>"
                             class="user-image">
                        <span><?= $user->username ?> <i class="caret"></i></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header light-blue">
                            <img src="<?= $user->userProfile->getAvatar('/images/anonymous.jpg') ?>"
                                 class="img-circle" alt="User Image"/>
                            <p>
                                <?= $user->username ?>
                        </li>

                        <!-- Menu Footer-->

                        <li class="user-footer">
                            <div class="pull-left">
                                <?php echo Html::a('Аккаунт', ['/site/account'], ['class' => 'btn btn-default btn-flat']) ?>
                            </div>
                            <div class="pull-right">
                                <?php echo Html::a('Выход', ['/site/logout'], ['class' => 'btn btn-default btn-flat', 'data-method' => 'post']) ?>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

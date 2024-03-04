<?php

/* @var $this yii\web\View */
/* @var $user \common\entities\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['account/reset-password', 'token' => $user->password_reset_token]);
?>
Здравствуйте, <?= $user->username ?>,

Для сброса пароля перейдите, пожалуйста, по ссылке:

<?= $resetLink ?>

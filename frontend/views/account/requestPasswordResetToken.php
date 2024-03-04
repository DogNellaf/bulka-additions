<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\forms\PasswordResetRequestForm */

$this->title = 'Запрос сброса пароля';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-request-password-reset page padded padded_bottom">
    <div class="wrapper">
        <div class="form">

            <div class="page_header">
                <div class="wrapper">
                    <div class="title title_1 font_2">
                        <?= \frontend\components\Service::strSplit(Html::encode($this->title)); ?>
                    </div>
                </div>
            </div>

            <p>Введите ваш E-mail, на него будет отправлена ссылка для сброса пароля.</p>
            <br>

            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

            <div class="form_input_wrap">

                <div class="form_input_block">
                    <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
                </div>
                <div class="form_input_wrap">
                    <div class="form_input_block submit_block">
                        <?= Html::submitButton(Yii::t('app', 'Отправить'), ['class' => 'common_btn black']) ?>
                    </div>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

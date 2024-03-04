<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\forms\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Сброс пароля';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-reset-password page padded padded_bottom">
    <div class="wrapper">
        <div class="form">

            <div class="page_header">
                <div class="wrapper">
                    <div class="title title_1 font_2">
                        <?= \frontend\components\Service::strSplit(Html::encode($this->title)); ?>
                    </div>
                </div>
            </div>

            <p>Введите новый пароль:</p>
            <br>

            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

            <div class="form_input_wrap">

                <div class="form_input_block">
                    <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>
                </div>
                <div class="form_input_wrap">
                    <div class="form_input_block submit_block">
                        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'common_btn black']) ?>
                    </div>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

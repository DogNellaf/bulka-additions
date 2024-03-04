<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\forms\SignupForm */

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-signup page padded padded_bottom">
    <div class="wrapper">
        <div class="form">

            <div class="page_header">
                <div class="wrapper">
                    <div class="title title_1 font_2">
                        <?= \frontend\components\Service::strSplit(Html::encode($this->title)); ?>
                    </div>
                </div>
            </div>

            <p>Заполните следующие поля для регистрации:</p>
            <br>

            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

            <div class="form_input_wrap">

                <div class="form_input_block">
                    <?= $form->field($model, 'phone')->textInput(['autofocus' => true, 'placeholder' => $model->getAttributeLabel('username') . ' *'])->label(false); ?>
                </div>
                <div class="form_input_block submit_block">
                    <?= Html::submitButton(Yii::t('app', 'Зарегистрироваться'), ['class' => 'common_btn black', 'name' => 'signup-button']) ?>
                </div>
            </div>


            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

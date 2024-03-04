<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\entities\UserAddress */
?>
<div class="add-address-page login-page">

    <div class="wrapper">
        <div class="ajax-cont">
            <div class="add_address_form_block">
                <div class="form">
                    <?php $form = ActiveForm::begin(); ?>

                    <div class="form_input_wrap">
                        <div class="form_input_block">
                            <?= $form->field($model, 'street')->textInput(['maxlength' => 255, 'placeholder' => $model->getAttributeLabel('street')])->label(false); ?>
                        </div>
                        <div class="form_input_block hidden">
                            <?= $form->field($model, 'house')->textInput(['maxlength' => 255, 'placeholder' => $model->getAttributeLabel('house')])->label(false); ?>
                        </div>
                        <div class="form_input_block">
                            <?= $form->field($model, 'apartment')->textInput(['maxlength' => 255, 'placeholder' => $model->getAttributeLabel('apartment')])->label(false); ?>
                        </div>
                        <div class="form_input_block">
                            <?= $form->field($model, 'floor')->textInput(['maxlength' => 255, 'placeholder' => $model->getAttributeLabel('floor')])->label(false); ?>
                        </div>
                        <div class="form_input_block">
                            <?= $form->field($model, 'entrance')->textInput(['maxlength' => 255, 'placeholder' => $model->getAttributeLabel('entrance')])->label(false); ?>
                        </div>
                        <div class="form_input_block">
                            <?= $form->field($model, 'intercom')->textInput(['maxlength' => 255, 'placeholder' => $model->getAttributeLabel('intercom')])->label(false); ?>
                        </div>
                        <div class="form_input_block">
                            <?= $form->field($model, 'note')->textarea(['rows' => 3, 'placeholder' => $model->getAttributeLabel('note')])->label(false); ?>
                        </div>
                        <div class="form_input_block submit_block">
                            <?= Html::submitButton('Сохранить', ['class' => 'common_btn']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
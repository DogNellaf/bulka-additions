<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model common\entities\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-6">
                    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'company_name')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'phone')->widget(MaskedInput::className(), [
                        'mask' => '+9 (999) 999-99-99',
                    ])->textInput(['maxlength' => true]); ?>

                    <?php if ($model->business) : ?>
                        <?= $form->field($model, 'wholesale')->checkbox() ?>
                    <?php endif; ?>

                    <?= $form->field($model, 'inn')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'kpp')->textInput(['maxlength' => true]) ?>

                    <?//= $form->field($model, 'status')->textInput() ?>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

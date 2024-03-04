<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;


/* @var $this yii\web\View */
/* @var $model common\entities\Reserves */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reserves-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box">
        <div class="box-body">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'restaurant_id')->textInput() ?>

            <?= $form->field($model, 'date')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'persons')->textInput() ?>

            <?= $form->field($model, 'notes')->textarea(['rows' => 6]) ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

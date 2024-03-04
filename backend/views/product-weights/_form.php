<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\entities\ProductWeights */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-weights-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-6">
                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'price')->textInput() ?>

                    <?= $form->field($model, 'business_price')->textInput() ?>

                    <?= $form->field($model, 'min_quantity')->textInput() ?>

                    <?= $form->field($model, 'balance')->textInput() ?>

                    <?= $form->field($model, 'id_1c')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

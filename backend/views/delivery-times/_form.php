<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\entities\DeliveryTimes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="delivery-times-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-6">
                    <?php if (in_array($model->id, [1,2,3])) : ?>
                        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                    <?php else: ?>
                        <?= $form->field($model, 'zone_id')->dropDownList($model::getZones()) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

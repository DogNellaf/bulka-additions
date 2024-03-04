<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\TimePicker;

/* @var $this yii\web\View */
/* @var $model common\entities\DeliveryTimeIntervals */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="delivery-time-intervals-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-6">
                    <div class="row">
                        <div class="col-6">
                            <?= $form->field($model, 'start')->widget(
                                TimePicker::class, [
                                'language' => 'ru',
                                'pluginOptions' => [
                                    'showSeconds' => false,
                                    'showMeridian' => false,
                                    'minuteStep' => 5,
                                    'defaultTime' => false,
                                ]
                            ]); ?>
                        </div>
                        <div class="col-6">
                            <?= $form->field($model, 'end')->widget(
                                TimePicker::class, [
                                'language' => 'ru',
                                'pluginOptions' => [
                                    'showSeconds' => false,
                                    'showMeridian' => false,
                                    'minuteStep' => 5,
                                    'defaultTime' => false,
                                ]
                            ]); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <?= $form->field($model, 'cost')->textInput() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

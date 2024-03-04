<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model common\entities\Restaurants */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="restaurants-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-6">
                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'worktime')->textarea(['rows' => 3, 'maxlength' => true]) ?>

                    <?= $form->field($model, 'lat')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'lng')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'additional_info')->widget(Widget::class, [
                        'settings' => [
                            'lang' => 'ru',
                            'minHeight' => 150,
                            'plugins' => [
                                'fullscreen'
                            ]
                        ]
                    ]); ?>

                </div>
                <div class="col-6">
                    <?php
                    $img = ($model->image_name) ? $model->image : '/images/default_thumb.png';
                    $label = Html::img($img, ['class' => 'preview_image_block', 'alt' => 'Image of ' . $model->id]) . "<span>Изображение</span>";
                    ?>
                    <?= $form->field($model, 'uploadedImageFile', ['options' => ['class' => 'img_input_block']])
                        ->fileInput(['class' => 'hidden-input img-input', 'accept' => 'image/*'])->label($label, ['class' => 'label-img']); ?>

                    <?= $form->field($model, 'alt')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-6">
                    <?= $form->field($model, 'meta_title')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'meta_keywords')->textarea(['rows' => 2, 'maxlength' => true]); ?>
                </div>
                <div class="col-6">
                    <?= $form->field($model, 'meta_description')->textarea(['rows' => 6, 'maxlength' => true]); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

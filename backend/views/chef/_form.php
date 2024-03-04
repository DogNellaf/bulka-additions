<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use vova07\imperavi\Widget;

/* @var $this yii\web\View */
/* @var $model \common\entities\Chef */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="products-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="box">
        <div class="box-body">
            <div class="row">
                <div>
                <?php
                    $img = ($model->image_name) ? $model->image : '/images/default_thumb.png';
                    $label = Html::img($img, ['class' => 'preview_image_block', 'alt' => 'Image of ' . $model->id]) . "<span>Изображение</span>";
                ?>
                <?= $form->field($model, 'uploadedImageFile', ['options' => ['class' => 'img_input_block']])
                    ->fileInput(['class' => 'hidden-input img-input', 'accept' => 'image/*'])->label($label, ['class' => 'label-img']);
                ?>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <?= $form->field($model, 'name_ru')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'description_ru')->widget(Widget::class, [
                        'settings' => [
                            'lang' => 'ru',
                            'minHeight' => 100,
                            'plugins' => [
                                'fullscreen'
                            ]
                        ]
                    ]); ?>
                    <?= $form->field($model, 'link_ru')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'href_ru')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-6">
                    <?= $form->field($model, 'name_en')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'description_en')->widget(Widget::class, [
                        'settings' => [
                            'lang' => 'ru',
                            'minHeight' => 100,
                            'plugins' => [
                                'fullscreen'
                            ]
                        ]
                    ]); ?>
                    <?= $form->field($model, 'link_en')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'href_en')->textInput(['maxlength' => true]) ?> 
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model common\entities\Galleries */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="galleries-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-6">
                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

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

    <?= $form->field($model, 'attachments')->widget(
        'backend\components\widget\GalleryUpload',
        [
            'url' => ['file-storage/upload'],
            'sortable' => true,
            'maxFileSize' => 20 * 1024 * 1024, // 2 MiB
            'acceptFileTypes' => new JsExpression('/(\.|\/)(gif|jpe?g|png)$/i'),
            'multiple' => true,
            'maxNumberOfFiles' => 20,
            'clientOptions' => []
        ]
    );?>

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

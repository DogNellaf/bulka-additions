<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\entities\Stories */
/* @var $form yii\widgets\ActiveForm */

$plugins = [
    'table',
//    'fontfamily',
    'fontsize',
    'clips',
    'fullscreen',
    'imagemanager',
//    'video',
];
?>

<div class="stories-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-6">
                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'html')->widget(Widget::class, [
                        'settings' => [
                            'lang' => 'ru',
                            'minHeight' => 200,
                            'imageUpload' => Url::to(['/file-storage/image-upload']),
                            'imageManagerJson' => Url::to(['/file-storage/images-get']),
                            'plugins' => $plugins
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
                    <hr>
                    <?php
                    $video = ($model->video_name) ? $model->video : '/images/default_thumb.png';
                    $label = Html::label($video, ['class' => 'preview_video_block', 'alt' => 'Video of ' . $model->id]) . "<span>Видео</span>";
                    ?>
                    <?= $form->field($model, 'uploadedVideoFile', ['options' => ['class' => 'img_input_block']])
                        ->fileInput(['class' => 'hidden-input img-input', 'accept' => 'video/*'])->label($label, ['class' => 'label-img']); ?>
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

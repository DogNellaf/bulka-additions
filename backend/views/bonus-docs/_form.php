<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
use yii\web\JsExpression;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model common\entities\BonusDocs */
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

                    <?=
                    $form->field($model, 'uploadedImageFile')->widget(FileInput::classname(), [
                        'options' => ['accept' => 'application/mp4'],
                    ]);
                    ;?>
                    <div class="form-group">
                        <?php if ($model->image_name) { echo $model->image_name;};?>
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

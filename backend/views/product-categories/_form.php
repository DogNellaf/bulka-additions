<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\entities\ProductCategories */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="school-categories-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-6">
                    <?php $sections = ArrayHelper::map(\common\entities\ProductSections::find()->having(['status' => 1])->asArray()->all(), 'id', 'title'); ?>
                    <?= $form->field($model, 'target')->dropDownList($sections) ?>

                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

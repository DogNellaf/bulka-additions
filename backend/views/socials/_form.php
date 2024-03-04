<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\entities\Socials */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="socials-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <?php if ($model->isNewRecord): ; ?>
            <div class="col-lg-6">
                <?= $form->field($model, 'icon')->dropDownList($model::VARIANTS, ['prompt' => 'Выберите соцсеть']) ?>
            </div>
        <?php endif; ?>
        <div class="col-lg-6">
            <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

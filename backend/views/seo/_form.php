<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\entities\Seo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="seo-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box">
        <div class="box-body">
            <?php if ($model->isNewRecord): ; ?>
                <?= $form->field($model, 'page')->textInput(['maxlength' => true]) ?>
            <?php endif; ?>
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
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success'])
        ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

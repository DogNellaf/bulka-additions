<?php
use backend\assets\AdminMapAsset;
AdminMapAsset::register($this);

use kartik\widgets\ColorInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;

/* @var $this yii\web\View */
/* @var $model common\entities\Modules */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="modules-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if (in_array($model->id, [7,])): ; ?>
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col-12">
<!--                        --><?//= Html::a('Draw mode', ['#'], ['class' => 'draw_mode btn btn-info']) ?>
<!--                        --><?//= Html::a('NoDraw mode', ['#'], ['class' => 'no_draw_mode btn btn-info']) ?>
<!--                        --><?//= Html::button('JSON', ['class' => 'gen_json btn btn-info']) ?>
<!--                        --><?//= Html::button('gen from JSON', ['class' => 'gen_from_json btn btn-info']) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <?php echo ColorInput::widget(['name' => 'shape-color','options' => ['readonly' => true]]); ?>
                    </div>
                    <div class="col-3">
                        <label>
                            <input type="checkbox" name="shape-mkad" class="shape_mkad">
                            В пределах МКАД
                        </label>
                        <br>
                        <label>
                            <input type="text" name="shape-id" class="shape_id" disabled="disabled">
                            ID
                        </label>
                        <br>
                        <label>
                            <input type="text" name="shape-title" class="shape_title">
                            Название зоны
                        </label>
                    </div>
                    <div class="col-6">
                        <?= Html::button('Удалить выделенную зону', ['id' => 'delete-shape-button', 'class' => 'btn btn-danger']) ?>
                        <?= $form->field($model, 'html')->hiddenInput(['class' => 'map_json'])->label(false); ?>
                        <? //= $form->field($model, 'html')->textarea(['rows' => 6, 'class' => 'form-control map_json'])->label(false); ?>
                    </div>
                </div>
                <p></p>
                <div class="row">
                    <div class="col-12">

                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="map">
                            <div id="map_place" class="map_place"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <?= Html::button('Сохранить', ['class' => 'btn btn-success map_submit_btn']) ?>
        </div>

        <style>
            .map {
                height: 36vw;
                min-height: 500px;
                width: 100%;
            }

            .map_place {
                position: absolute;
                width: 100%;
                height: 100%;
                left: 0;
                top: 0;
            }
        </style>

        <?php $this->beginBlock('scripts') ?>
        <script>
            $(document).ready(function () {

                initAdminMap();
            });
        </script>
        <?php $this->endBlock() ?>
    <?php endif; ?>

    <?php if (!in_array($model->id, [7,])): ; ?>
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col-6">
                        <?php if (!in_array($model->id, [3,5,6,9,])): ; ?>
                            <?= $form->field($model, 'title')->textarea(['rows' => 1, 'maxlength' => true]) ?>
                        <?php endif; ?>

                        <?/*= $form->field($model, 'description')->widget(Widget::class, [
                        'settings' => [
                            'lang' => 'ru',
                            'minHeight' => 200,
                        ]
                    ]); */?>

                        <?php if (!in_array($model->id, [3,5,6,9,])): ; ?>
                            <?= $form->field($model, 'html')->widget(Widget::class, [
                                'settings' => [
                                    'lang' => 'ru',
                                    'minHeight' => 200,
                                ]
                            ]); ?>
                        <?php endif; ?>

                        <?php if (in_array($model->id, [1,4,11,]) or $model->isNewRecord): ; ?>
                            <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>
                        <?php endif; ?>

                        <?php if (in_array($model->id, [9,])): ; ?>
                            <?= $form->field($model, 'min_order_sum')->textInput(['maxlength' => true]) ?>
                            <?= $form->field($model, 'min_free_delivery_sum')->textInput(['maxlength' => true]) ?>
                            <?= $form->field($model, 'min_free_delivery_sum_outer_mkad')->textInput(['maxlength' => true]) ?>
                        <?php endif; ?>
                    </div>
                    <div class="col-6">
                        <?php if (!in_array($model->id, [2,8,9,12,14])): ; ?>
                            <?php
                            $img = ($model->image_name) ? $model->image : '/images/default_thumb.png';
                            $label = Html::img($img, ['class' => 'preview_image_block', 'alt' => 'Image of ' . $model->id]) . "<span>Изображение</span>";
                            ?>
                            <?= $form->field($model, 'uploadedImageFile', ['options' => ['class' => 'img_input_block']])
                                ->fileInput(['class' => 'hidden-input img-input', 'accept' => 'image/*'])->label($label, ['class' => 'label-img']); ?>

                            <?= $form->field($model, 'alt')->textInput(['maxlength' => true]) ?>
                        <?php endif; ?>

                        <br>

                        <?php if (in_array($model->id, [1,4,11,]) or $model->isNewRecord): ; ?>
                            <?php
                            $img = ($model->image_name_2) ? $model->image2 : '/images/default_thumb.png';
                            $label = Html::img($img, ['class' => 'preview_image_block', 'alt' => 'Image 2 of ' . $model->id]) . "<span>Изображение 2</span>";
                            ?>
                            <?= $form->field($model, 'uploadedImage2File', ['options' => ['class' => 'img_input_block']])
                                ->fileInput(['class' => 'hidden-input img-input', 'accept' => 'image/*'])->label($label, ['class' => 'label-img']); ?>

                            <?= $form->field($model, 'alt2')->textInput(['maxlength' => true]) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>
    <?php endif; ?>

    <?php ActiveForm::end(); ?>

</div>

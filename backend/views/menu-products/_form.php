<?php

use common\entities\MenuCategories;
use common\entities\MenuIcons;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use vova07\imperavi\Widget;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model \common\entities\MenuProducts */
/* @var $icons \common\entities\MenuIcons */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="products-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-8">
                    <?php if (!$model->isNewRecord): ; ?>
                        <?php $categories = ArrayHelper::map(MenuCategories::find()->having(['status' => 1])->asArray()->all(), 'id', 'title_ru'); ?>
                        <?= $form->field($model, 'category_id')->dropDownList($categories) ?>
                    <?php endif; ?>
                    <h2>Иконка названия продукта</h2>
                    <?php foreach ($icons as $icon){ ?>
                        <label>
                            <img height="50px" src="/files/menu_icons/<?=  $icon->image_name?>" alt="<?= $icon->image_name ?>"><br>
                            <input <?= ($model->title_icon === $icon->id) ? 'checked' : '' ?>  type="checkbox" name="title_icon" value="<?= $icon->image_name?>">
                        </label>
                    <?php }?>
                    <h2>Иконка описание продукта</h2>
                    <?php foreach ($icons as $icon){ ?>
                        <label>
                            <img height="50px" src="/files/menu_icons/<?=  $icon->image_name?>" alt="<?= $icon->image_name ?>"><br>
                            <input  <?= ($model->desc_icon === $icon->id) ? 'checked' : '' ?> type="checkbox" name="desc_icon" value="<?= $icon->image_name?>">
                        </label>
                    <?php }?>
                    <h2>Иконка ссылки продукта</h2>
                    <?php foreach ($icons as $icon){ ?>
                        <label>
                            <img height="50px" src="/files/menu_icons/<?=  $icon->image_name?>" alt="<?= $icon->image_name ?>"><br>
                            <input  <?= ($model->link_icon === $icon->id) ? 'checked' : '' ?> type="checkbox" name="link_icon" value="<?= $icon->image_name?>">
                        </label>
                    <?php }?>
                    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-4">
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
                    <?= $form->field($model, 'title_ru')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'title_desc_ru')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'additional_ru')->textInput(['maxlength' => true]) ?>
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
                    <?= $form->field($model, 'title_en')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'title_desc_en')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'additional_en')->textInput(['maxlength' => true]) ?>
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

<?php $this->beginBlock('scripts') ?>
<script src='/js/vendor/jquery-sortable.js'></script>
<script>
    var dragit;
    $(document).ready(function () {
        dragit = $("ol.dragit").sortable({
            onDrop: function ($item, container, _super) {
                var data = dragit.sortable("serialize").get();
                var jsonString = JSON.stringify(data, null, ' ');

                _super($item, container);
                $.ajax({
                    data: {id:<?=$model->id?>, sort: jsonString},
                    url: "/admin/products/products-sort",
                    type: 'POST',
                    success: function (res) {
                        console.log(res);
                    },
                });
            }
        });
    });
</script>
<?php $this->endBlock() ?>

<?php
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use vova07\imperavi\Widget;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model \common\entities\Products */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="products-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-8">
                    <?php if (!$model->isNewRecord): ; ?>
                        <?php $categories = ArrayHelper::map(\common\entities\ProductCategories::find()->having(['status' => 1])->asArray()->all(), 'id', 'title'); ?>
                        <?= $form->field($model, 'category_id')->dropDownList($categories) ?>
                    <?php endif; ?>

                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'mainpage_status')->checkbox() ?>
                    <div class="row">
                        <div class="col-6">
                            <?//= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-6">
                            <?//= $form->field($model, 'weight')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <?= $form->field($model, 'description')->widget(Widget::class, [
                        'settings' => [
                            'lang' => 'ru',
                            'minHeight' => 100,
                            'plugins' => [
                                'fullscreen'
                            ]
                        ]
                    ]); ?>
                    <div class="row">
                        <div class="col-6">
                            <?//= $form->field($model, 'id_1c')->textInput() ?>
                        </div>
                        <div class="col-6">
                            <?= $form->field($model, 'sku')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <?= $form->field($model, 'proteins')->textInput(['maxlength' => true]) ?>
                            <?= $form->field($model, 'fats')->textInput(['maxlength' => true]) ?>
                            <?= $form->field($model, 'carbohydrates')->textInput(['maxlength' => true]) ?>
                            <?= $form->field($model, 'kcal')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-6">
                            <?= $form->field($model, 'min_delivery_days')->textInput() ?>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <?php
                    $img = ($model->image_name) ? $model->image : '/images/default_thumb.png';
                    $label = Html::img($img, ['class' => 'preview_image_block', 'alt' => 'Image of ' . $model->id]) . "<span>Изображение</span>";
                    ?>
                    <?= $form->field($model, 'uploadedImageFile', ['options' => ['class' => 'img_input_block']])
                        ->fileInput(['class' => 'hidden-input img-input', 'accept' => 'image/*'])->label($label, ['class' => 'label-img']); ?>
                </div>
            </div>


            <?php
            /*
            ?>
            <div class="row">
                <div class="col-12">
                    <?
                    $relProducts = \yii\helpers\ArrayHelper::map(\common\entities\Products::find()->andWhere(['<>', 'id', $model->id])->asArray()->all(), 'id', 'title');
                    ?>
                    <?= $form->field($model, 'rel_products')->checkboxList($relProducts,[
                        //'multiple' => 'multiple',
                        //'size' => 20,
                    ]) ?>
                </div>
            </div>
            <?php
            */
            ?>


            <div class="box box-primary">
                <div class="box-body table-responsive">
                    <h4>Похожие товары</h4>
                    <ol class="dragit">
                        <?php foreach ($model->getProductsList() as $item) { ?>
                            <li data-id='<?= $item->id ?>'><a href='<?= Url::to(['/products/view', 'id' => $item->id]) ?>'><?= $item->title ?></a>
                                <?= Html::submitButton('<i class="fa fa-remove"></i></li>', ['name' => 'sbmt', 'value' => 'deladdit_' . $item->id, 'class' => 'btn btn-danger btn-flat btn-sm btn-xs',
                                    'data' => [
                                        'confirm' => 'Вы уверены, что хотите удалить элемент?',
                                        'method' => 'post',
                                    ],]); ?>
                            </li>
                        <?php } ?>
                    </ol>
                    <?= Select2::widget([
                        'options' => ['multiple' => false, 'placeholder' => 'Добавьте продукт'],
                        'name' => 'additem',
                        'pluginOptions' => [
                            'allowClear' => true,
                            'value' => '',
                            'minimumInputLength' => 2,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'Загрузка.....'; }"),
                            ],
                            'ajax' => [
                                'url' => '/admin/products/list',
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(city) { return city.text; }'),
                            'templateSelection' => new JsExpression('function (city) { console.log(city); return city.text; }'),
                        ],
                    ]); ?>
                </div>
                <div class="box-footer">
                    <?= Html::submitButton('Добавить продукт', ['name' => 'sbmt', 'value' => 'additem', 'class' => 'btn btn-warning btn-flat']) ?>
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

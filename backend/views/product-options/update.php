<?php
/* @var $this yii\web\View */
/* @var $model common\entities\ProductOptions */

$this->title = 'Изменить: ' . $model->title;

$this->params['breadcrumbs'][] = ['label' => $model->product->title, 'url' => ['index', 'id' => $model->product_id]];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="product-options-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

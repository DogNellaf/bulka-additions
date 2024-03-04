<?php
/* @var $this yii\web\View */
/* @var $model common\entities\ProductWeights */

$this->title = 'Изменить: ' . $model->title;

$this->params['breadcrumbs'][] = ['label' => $model->product->title, 'url' => ['index', 'slug' => $model->product->slug]];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="product-weights-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

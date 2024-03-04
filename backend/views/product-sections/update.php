<?php
/* @var $this yii\web\View */
/* @var $model common\entities\ProductSections */

$this->title = 'Изменить: ' . $model->title;

$this->params['breadcrumbs'][] = ['label' => 'Разделы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="product-sections-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

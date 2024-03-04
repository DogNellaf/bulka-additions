<?php

/* @var $this yii\web\View */
/* @var $model \common\entities\ProductCategories */

$this->title = 'Изменить: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="gallery-categories-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

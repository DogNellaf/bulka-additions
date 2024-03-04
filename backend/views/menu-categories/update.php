<?php

/* @var $this yii\web\View */
/* @var $model \common\entities\MenuCategories */

$this->title = 'Изменить: ' . $model->title_ru;
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title_ru, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="gallery-categories-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

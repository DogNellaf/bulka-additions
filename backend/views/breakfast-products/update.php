<?php
/* @var $this yii\web\View */
/* @var $model \common\entities\BreakfastProducts */
/* @var $icons \common\entities\MenuIcons */

$this->title = 'Изменить: ' . $model->title_ru;

$this->params['breadcrumbs'][] = ['label' => $model->category->title_ru, 'url' => ['index', 'slug' => $model->category->slug]];
$this->params['breadcrumbs'][] = ['label' => $model->title_ru, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="products-update">

    <?= $this->render('_form', [
        'model' => $model,
        'icons' => $icons,
    ]) ?>

</div>

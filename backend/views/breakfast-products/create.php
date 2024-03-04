<?php
/* @var $this yii\web\View */
/* @var $model \common\entities\BreakfastProducts */
/* @var $icons \common\entities\MenuIcons */
/* @var $category \common\entities\BreakfastCategories */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => $category->title_ru, 'url' => ['index', 'slug' => $category->slug]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-create">

    <?= $this->render('_form', [
        'model' => $model,
        'icons' => $icons,
    ]) ?>

</div>

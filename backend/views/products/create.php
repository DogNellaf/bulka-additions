<?php
/* @var $this yii\web\View */
/* @var $model \common\entities\Products */
/* @var $category \common\entities\ProductCategories */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => $category->title, 'url' => ['index', 'slug' => $category->slug]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

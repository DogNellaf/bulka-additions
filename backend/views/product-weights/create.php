<?php
/* @var $this yii\web\View */
/* @var $model common\entities\ProductWeights */
/* @var $category \common\entities\Products */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => $category->title, 'url' => ['index', 'slug' => $category->slug]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-weights-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

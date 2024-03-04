<?php

/* @var $this yii\web\View */
/* @var $model \common\entities\ProductCategories */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gallery-categories-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

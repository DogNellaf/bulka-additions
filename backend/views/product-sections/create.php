<?php

/* @var $this yii\web\View */
/* @var $model common\entities\ProductSections */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Разделы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-sections-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

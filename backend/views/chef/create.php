<?php
/* @var $this yii\web\View */
/* @var $model \common\entities\Chef */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Шеф повар', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

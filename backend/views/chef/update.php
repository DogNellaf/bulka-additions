<?php
/* @var $this yii\web\View */
/* @var $model \common\entities\Chef */


$this->title = 'Изменить: ' . $model->name_ru;

$this->params['breadcrumbs'][] = ['label' => $model->name_ru, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="products-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php
/* @var $this yii\web\View */
/* @var $model \common\entities\MenuIcons */

$this->title = 'Изменить: ' . $model->title;

$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="products-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

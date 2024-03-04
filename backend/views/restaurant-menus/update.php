<?php
/* @var $this yii\web\View */
/* @var $model common\entities\RestaurantMenus */

$this->title = 'Изменить: ' . $model->title;

$this->params['breadcrumbs'][] = ['label' => $model->target->title, 'url' => ['index', 'id' => $model->target_id]];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="restaurant-menus-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

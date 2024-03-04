<?php
/* @var $this yii\web\View */
/* @var $model common\entities\DeliveryTimeIntervals */

$this->title = 'Изменить: ' . $model->id;

$this->params['breadcrumbs'][] = ['label' => ($model->target->getZoneTitle()) ? $model->target->getZoneTitle() : $model->target->title, 'url' => ['index', 'id' => $model->target_id]];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="delivery-time-intervals-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

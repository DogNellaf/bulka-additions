<?php
/* @var $this yii\web\View */
/* @var $model common\entities\DeliveryTimes */

$this->title = 'Изменить: ' . ($model->getZoneTitle()) ? $model->getZoneTitle() : $model->title;

$this->params['breadcrumbs'][] = ['label' => 'Интервалы доставки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => ($model->getZoneTitle()) ? $model->getZoneTitle() : $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="delivery-times-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

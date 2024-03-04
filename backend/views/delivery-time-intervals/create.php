<?php
/* @var $this yii\web\View */
/* @var $model common\entities\DeliveryTimeIntervals */
/* @var $category \common\entities\DeliveryTimes */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => ($category->getZoneTitle())? $category->getZoneTitle() : $category->title, 'url' => ['index', 'id' => $category['id']]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="delivery-time-intervals-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

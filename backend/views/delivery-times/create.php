<?php
/* @var $this yii\web\View */
/* @var $model common\entities\DeliveryTimes */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Интервалы доставки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="delivery-times-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

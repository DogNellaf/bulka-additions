<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\entities\DeliveryTimes;

/* @var $this yii\web\View */
/* @var $model common\entities\DeliveryTimes */

$this->title = ($model->getZoneTitle()) ? $model->getZoneTitle() : $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Интервалы доставки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="delivery-times-view">

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы точно хотите удалить эту запись?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Интервалы', ['/delivery-time-intervals', 'id' => $model->id], ['class' => 'btn btn-info', 'data-pjax' => 0]) ?>

        <?php if ($model->status) {
            echo Html::a('<span class="glyphicon glyphicon-ok"></span> Отображать', ['status', 'id' => $model->id], ['class' => 'btn btn-success btn-raised pull-right']);
        } else {
            echo Html::a('<span class="glyphicon glyphicon-remove"></span> Скрывать', ['status', 'id' => $model->id], ['class' => 'btn btn-danger btn-raised pull-right']);
        }; ?>
    </p>
    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'title',
                    [
                        'attribute' => 'zone_id',
                        'value' => function (DeliveryTimes $data) {
                            return $data->getZoneTitle();
                        }
                    ],
                    'sort',
                    'status',
                ],
            ]) ?>

        </div>
    </div>
</div>

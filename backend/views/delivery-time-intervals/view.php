<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\entities\DeliveryTimeIntervals;

/* @var $this yii\web\View */
/* @var $model common\entities\DeliveryTimeIntervals */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => ($model->target->getZoneTitle()) ? $model->target->getZoneTitle() : $model->target->title, 'url' => ['index', 'id' => $model->target_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="delivery-time-intervals-view">

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы точно хотите удалить эту запись?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Назад', ['/delivery-times'], ['class' => 'btn btn-info', 'data-pjax' => 0]) ?>
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
                    //'target_id',
                    'start',
                    'end',
                    'cost',
                    'sort',
                    'status',
                ],
            ]) ?>

        </div>
    </div>
</div>

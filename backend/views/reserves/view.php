<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\entities\Reserves;

/* @var $this yii\web\View */
/* @var $model common\entities\Reserves */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Резерв', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reserves-view">

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' =>
            'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы точно хотите удалить эту запись?',
                'method' => 'post',
            ],
        ]) ?>
        <?php if ($model->status) {
            echo Html::a('<span class="glyphicon glyphicon-ok"></span> Обработан', ['status', 'id' => $model->id], ['class' => 'btn btn-success btn-raised pull-right']);
        } else {
            echo Html::a('<span class="glyphicon glyphicon-remove"></span> Ожидает', ['status', 'id' => $model->id], ['class' => 'btn btn-danger btn-raised pull-right']);
        }; ?>
    </p>
    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'name',
                    'phone',
                    [
                        'attribute' => 'restaurant_id',
                        'format' => 'raw',
                        'value' => function (Reserves $data) {
                            if ($data->restaurant_id && $restaurant = \common\entities\Restaurants::findOne($data->restaurant_id)) {
                                return $restaurant->title;
                            }
                            return null;
                        },
                    ],
                    'date',
                    'persons',
                    'notes:ntext',
                    'created_at:datetime',
                ],
            ]) ?>

        </div>
    </div>
</div>

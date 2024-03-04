<?php

use yii\widgets\Pjax;
use arogachev\sortable\grid\SortableColumn;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use common\entities\DeliveryTimes;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Интервалы доставки';
$this->params['breadcrumbs'][] = $this->title;

//todo pjax?
?>
<div class="delivery-times-index">

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-12">
                    <p>
                        При отрисовке зоны на карте можно задать ее произвольное название, желательно уникальное, для облегчения поиска.
                    </p>
                    <p>
                        После отрисовки на карте новой зоны необходимо добавить новую зону с интервалами.
                    </p>
                    <p>
                        Интервалы "В пределах МКАД", "За пределы МКАД" используются по умолчанию, если для зоны не созданы интервалы.
                    </p>
                    <p>
                        Разделы "Самовывоз", "В пределах МКАД", "За пределы МКАД" удалять ЗАПРЕЩЕНО.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="box">
        <div class="box-body">
            <div class="question-index" id="question-sortable">
                <?php Pjax::begin(); ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                            /*
                        'sort',
                        [
                            'class' => SortableColumn::class,
                            'gridContainerId' => 'question-sortable',
                            'baseUrl' => '/admin/sort/', // Optional, defaults to '/sort/'
                            'confirmMove' => false, // Optional, defaults to true
                            'template' => '<div class="sortable-section">{currentPosition}</div>
                                           <div class="sortable-section">{moveWithDragAndDrop}</div>'
                        ],
                            */

                        //'id',
                        'title',
                        [
                            'label' => 'id зоны на карте',
                            'format' => 'raw',
                            'value' => function (DeliveryTimes $data) {
                                return $data->zone_id;
                            }
                        ],
                        [
                            'attribute' => 'zone_id',
                            'value' => function (DeliveryTimes $data) {
                                return $data->getZoneTitle();
                            }
                        ],
                        [
                            'label' => 'Интервалы',
                            'format' => 'raw',
                            'value' => function ($data) {
                                return Html::a('Интервалы', ['/delivery-time-intervals', 'id' => $data->id], ['class' => 'btn btn-info btn-raised', 'data-pjax' => 0]);
                            }
                        ],
                        /*
                        [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'value' => function ($data) {
                                if ($data->status) {
                                    return Html::a('<span class="glyphicon glyphicon-ok"></span> Отображать', Url::to(['status', 'id' => $data->id]), ['class' => 'btn btn-success btn-raised']);
                                } else {
                                    return Html::a('<span class="glyphicon glyphicon-remove"></span> Скрывать', Url::to(['status', 'id' => $data->id]), ['class' => 'btn btn-danger btn-raised']);
                                }
                            },
                            'options' => ['style' => 'width: 150px; max-width: 150px;'],
                        ],
                        */

                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>

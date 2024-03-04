<?php

use yii\widgets\Pjax;
use arogachev\sortable\grid\SortableColumn;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use common\entities\RestaurantTexts;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $category \common\entities\Restaurants */

$this->title = $category->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="restaurant-texts-index">

    <p>
        <?= Html::a('Добавить', ['create', 'id' => $category->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Ресторан', ['/restaurants/view', 'id' => $category->id], ['class' => 'btn btn-info', 'data-pjax' => 0]) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <div class="question-index" id="question-sortable">
                <?php Pjax::begin(); ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        'sort',
                        [
                            'class' => SortableColumn::class,
                            'gridContainerId' => 'question-sortable',
                            'baseUrl' => '/admin/sort/', // Optional, defaults to '/sort/'
                            'confirmMove' => false, // Optional, defaults to true
                            'template' => '<div class="sortable-section">{currentPosition}</div>
                                           <div class="sortable-section">{moveWithDragAndDrop}</div>'
                        ],

                        //'id',
                        //'target_id',
                        'title',
                        [
                            'label' => 'Изображение',
                            'format' => 'raw',
                            'value' => function ($data) {
                                if ($data->image_name) {

                                    return Html::img($data->image, [
                                        'alt' => 'yii2 - картинка в gridview',
                                        'style' => 'height:100px;'
                                    ]);
                                }
                                return false;
                            },
                        ],
                        //'link',
                        //'link_title',
                        //'html:ntext',
                        //'image_name',
                        //'alt',
                        //'sort',
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

                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>

<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\entities\Products;
use arogachev\sortable\grid\SortableColumn;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $category \common\entities\ProductCategories */

$this->title = $category->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-index">
    <p>
        <?= Html::a('Добавить', ['create', 'slug' => $category->slug], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <div class="question-index" id="question-sortable">
                <?php Pjax::begin(); ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        'sort',
                        [
                            'class' => SortableColumn::class,
                            'gridContainerId' => 'question-sortable',
                            'baseUrl' => '/admin/sort/', // Optional, defaults to '/sort/'
                            'confirmMove' => false, // Optional, defaults to true
//                            'template' => '<div class="sortable-section">{currentPosition}</div>
//                                           <div class="sortable-section">{moveWithDragAndDrop}</div>'
                        ],
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
                        //'price',
                        [
                            'label' => 'Вес/цена',
                            'format' => 'raw',
                            'value' => function (Products $data) {
                                return Html::a('Вес/цена', ['/product-weights', 'slug' => $data->slug], ['class' => 'btn btn-info btn-raised', 'data-pjax' => 0]);
                            }
                        ],
                        /*
                        [
                            'label' => 'Опции',
                            'format' => 'raw',
                            'value' => function (Products $data) {
                                return Html::a('Опции', Url::to(['/product-options/index', 'id' => $data->id]), ['class' => 'btn btn-info', 'data-pjax' => 0]);
                            }
                        ],
                        */
                        [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'value' => function (Products $data) {
                                if ($data->status) {
                                    return Html::a('<span class="glyphicon glyphicon-ok"></span> Отображать', ['status', 'id' => $data->id], ['class' => 'btn btn-success btn-raised']);
                                } else {
                                    return Html::a('<span class="glyphicon glyphicon-remove"></span> Скрывать', ['status', 'id' => $data->id], ['class' => 'btn btn-danger btn-raised']);
                                }
                            }
                        ],
                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>

<?php

use common\entities\MenuCategories;
use common\entities\MenuProducts;
use yii\helpers\Html;
use yii\grid\GridView;
use arogachev\sortable\grid\SortableColumn;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $category MenuCategories */

$this->title = $category->title_ru;
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
                        'title_ru',
                        [
                            'label' => 'Изображение',
                            'format' => 'raw',
                            'value' => function ($data) {
                                if ($data->image) {

                                    return Html::img($data->image, [
                                        'alt' => 'yii2 - картинка в gridview',
                                        'style' => 'height:100px;'
                                    ]);
                                }
                                return false;
                            },
                        ],

                        'price',
                        [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'value' => function (MenuProducts $data) {
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

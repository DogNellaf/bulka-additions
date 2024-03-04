<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\entities\Socials;
use yii\widgets\Pjax;
use arogachev\sortable\grid\SortableColumn;

/* @var $this yii\web\View */
/* @var $searchModel common\entities\Socials */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Соцсети';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="socials-index">

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
                        [
                            'label' => 'Название',
                            'format' => 'raw',
                            'value' => function (Socials $data) {
                                return $data::VARIANTS[$data->icon];
                            },
                        ],
                        [
                            'attribute' => 'link',
                            'format' => 'raw',
                            'value' => function (Socials $data) {
                                return Html::a($data->link, $data->link, ['target' => '_blank']);
                            }
                        ],
                        [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'value' => function (Socials $data) {
                                if ($data->status) {
                                    return Html::a('<span class="glyphicon glyphicon-ok"></span> Отображать', ['status', 'id' => $data->id], ['class' => 'btn btn-success btn-raised']);
                                } else {
                                    return Html::a('<span class="glyphicon glyphicon-remove"></span> Скрывать', ['status', 'id' => $data->id], ['class' => 'btn btn-danger btn-raised']);
                                }
                            },
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'headerOptions' => ['width' => '80'],
                            'template' => '{update}',
                        ],
                    ],
                ]); ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>


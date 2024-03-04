<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use common\entities\Callbacks;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Сообщения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="callbacks-index">

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'attribute' => 'created_at',
                        'format' => 'raw',
                        'value' => function ($data) {
                            return Yii::$app->formatter->asTime($data->created_at, 'dd.MM.yyyy H:mm:ss');
                        },
                        'options' => ['width' => '200'],
                    ],
                    [
                        'attribute' => 'name',
                        'contentOptions' => ['style' => 'white-space: normal;']
                    ],
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'value' => function ($data) {
                            if ($data->status) {
                                return Html::a("<span class=\"glyphicon glyphicon-ok\"></span> Обработан", Url::to(['status', 'id' => $data->id]), ['class' => 'btn btn-success btn-raised']);
                            } else {
                                return Html::a('<span class="glyphicon glyphicon-remove"></span> Ожидает', Url::to(['status', 'id' => $data->id]), ['class' => 'btn btn-danger btn-raised']);
                            }
                        },
                        'options' => ['style' => 'width: 150px; max-width: 150px;'],
                    ],
                    //'phone',
                    //'email:email',
                    //'notes:ntext',
                    //'created_at',
                    //'status',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        //'header'=>'Действия',
                        'headerOptions' => ['width' => '80'],
                        'template' => '{view} {delete} {link}',
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>

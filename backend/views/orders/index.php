<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\DatePicker;
use common\entities\Orders;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function (Orders $data) {
                    if ($data->user_id) {
                        return Html::a($data->user->username, ['user/view', 'id' => $data->user_id]);
                    } else {
                        return $data->name;
                    }
                },
            ],
            'created_at:datetime',
            'email:email',
            /*
            [
                'attribute' => 'datetime',
                'format' => 'raw',
                'value' => function (Orders $data) {
                    return $data->datetime ? Yii::$app->formatter->asDate($data->datetime, 'dd.MM.yyyy') : '';
                },
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'datetime',
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'pluginOptions' => [
                        'todayHighlight' => true,
                        'todayBtn' => true,
                        'autoclose' => true,
                        'format' => 'dd.mm.yyyy',
                    ]
                ]),
                'options' => ['width' => '200'],
            ],
            */
            /*
            [
                'attribute' => 'user_status',
                'format' => 'raw',
                'value' => function (Orders $data) {
                    if (!$data->user_status) {
                        return Html::a("<span class=\"glyphicon glyphicon-ok\"></span> Да", '#', ['class' => 'text-success']);
                    } else {
                        return Html::a('<span class="glyphicon glyphicon-remove"></span> Нет', '#', ['class' => 'text-danger']);
                    }
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'user_status',
                    ['0' => 'Да', '1' => 'Нет'],
                    ['class' => 'form-control', 'prompt' => 'Все']
                ),
                'options' => ['style' => 'width: 100px; max-width: 100px;'],
            ],
            */
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function (Orders $data) {
                    if ($data->status) {
                        return Html::a("<span class=\"glyphicon glyphicon-ok\"></span> Обработан", Url::to(['status', 'id' => $data->id]), ['class' => 'btn btn-success btn-raised']);
                    } else {
                        return Html::a('<span class="glyphicon glyphicon-remove"></span> Ожидает', Url::to(['status', 'id' => $data->id]), ['class' => 'btn btn-danger btn-raised']);
                    }
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'status',
                    ['0' => 'Ожидает', '1' => 'Обработан'],
                    ['class' => 'form-control', 'prompt' => 'Все']
                ),
                'options' => ['style' => 'width: 150px; max-width: 125px;'],
            ],
            [
                'attribute' => 'pay_method',
                'format' => 'raw',
                'value' => function (Orders $data) {
                    return Yii::$app->params['payMethods'][$data->pay_method];
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'pay_method',
                    Yii::$app->params['payMethods'],
                    ['class' => 'form-control', 'prompt' => 'Все']
                ),
                'options' => ['style' => 'width: 150px; max-width: 125px;'],
            ],
            [
                'attribute' => 'ref_url',
                'value' => function ($model) {
                    return \yii\helpers\StringHelper::truncate($model->ref_url, 50);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['width' => '80'],
                'template' => '{view} {delete} {link}',
            ],
        ],
    ]); ?>
</div>

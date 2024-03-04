<?php

use common\entities\Orders;
use common\entities\OrderItems;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use common\components\DropdownParams;

/* @var $this yii\web\View */
/* @var $model \common\entities\Orders */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$params = new DropdownParams();
?>
<div class="orders-view">

    <p>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'acquirer_order_id',
            'created_at:datetime',
            'quantity',
            'cost',
            'delivery_cost',
            'name',
            'phone',
            'email:email',
            //'address',
            'street',
            'house',
            'apartment',
            'floor',
            'entrance',
            'intercom',
            /*
            [
                'attribute' => 'datetime',
                'format' => 'raw',
                'value' => function (Orders $data) {
                    return $data->datetime ? Yii::$app->formatter->asDate($data->datetime, 'dd.MM.yyyy HH:mm') : '';
                }
            ],
            */
            'delivery_date',
            'delivery_time',
            [
                'attribute' => 'delivery_method',
                'format' => 'raw',
                'value' => function (Orders $data) {
                    return Yii::$app->params['deliveryMethods'][$data->delivery_method];
                },
            ],
            [
                'attribute' => 'delivery_pickup_point',
                'format' => 'raw',
                'value' => function (Orders $data) {
                    if ($data->delivery_pickup_point && $restaurant = \common\entities\Restaurants::findOne($data->delivery_pickup_point)) {
                        return $restaurant->title;
                    }
                    return null;
                },
            ],
            [
                'attribute' => 'pay_method',
                'format' => 'raw',
                'value' => function (Orders $data) {
                    return Yii::$app->params['payMethods'][$data->pay_method];
                },
            ],
            'note:ntext',
            [
                'attribute' => 'user_status',
                'format' => 'raw',
                'value' => function (Orders $data) {
                    if (!$data->user_status) {
                        return Html::a("<span class=\"glyphicon glyphicon-ok\"></span> Да", '#', ['class' => 'text-success']);
                    } else {
                        return Html::a('<span class="glyphicon glyphicon-remove"></span> Нет', '#', ['class' => 'text-danger']);
                    }
                }
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function (Orders $data) {
                    if ($data->status) {
                        return Html::a("<span class=\"glyphicon glyphicon-ok\"></span> Обработан", Url::to(['status', 'id' => $data->id]), ['class' => 'btn btn-success btn-raised']);
                    } else {
                        return Html::a('<span class="glyphicon glyphicon-remove"></span> Ожидает', Url::to(['status', 'id' => $data->id]), ['class' => 'btn btn-danger btn-raised']);
                    }
                }
            ],
            [
                'label' => 'xml',
                'format' => 'raw',
                'value' => function (Orders $data) {
                    $fileFullPath = Yii::getAlias('@orders_xml_local') . '/bulka_' . $data->id . '.xml';
                    if (file_exists($fileFullPath)) {
                        return 'Сформирован';
                    } elseif (!$data->xml_formed) {
                        $str = 'Не было попыток формирования файла. ';
                    } else {
                        $str = 'Файл не найден. ';
                    }
                    $str .= Html::a('Сформировать xml', Url::to(['form-xml', 'id' => $data->id]), ['class' => 'btn btn-danger btn-raised']);
                    return $str;
                }
            ],
            [
                'attribute' => 'sms_result',
                'format' => 'raw',
                'value' => function (Orders $data) {
                    $str = $data->sms_result;
                    if ($data->sms_result != 'OK') {
                        $str .= ' ' . Html::a('Отправить смс', Url::to(['send-sms', 'id' => $data->id]), ['class' => 'btn btn-danger btn-raised']);
                    }
                    return $str;
                }
            ],
            [
                'attribute' => 'email_result',
                'format' => 'raw',
                'value' => function (Orders $data) {
                    $str = $data->email_result;
                    if (!$data->email_result) {
                        $str .= 'Не было попыток отправки ';
                    }
                    if ($data->email_result != 'OK') {
                        $str .= ' ' . Html::a('Отправить email', Url::to(['send-email', 'id' => $data->id]), ['class' => 'btn btn-danger btn-raised']);
                    }
                    return $str;
                }
            ],
            'ref_url',
        ],
    ]) ?>

    <h3>Заказы</h3>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Изображение',
                'format' => 'raw',
                'value' => function (OrderItems $data) {
                    return Html::img($data->product->image, [
                        'alt' => 'yii2 - картинка в gridview',
                        'style' => 'width:100px;'
                    ]);
                },
            ],
            [
                'label' => 'Наименование',
                'format' => 'raw',
                'value' => function (OrderItems $data) {
                    return Html::a($data->product->title, ['products/view', 'id' => $data->product_id]);
                },
            ],
            [
                'label' => 'Вес',
                'format' => 'raw',
                'value' => function (OrderItems $data) {
                    return Html::a($data->getWeight()->title, ['product-weights/view', 'id' => $data->weight]);
                },
            ],
            [
                'label' => 'Опции',
                'format' => 'raw',
                'value' => function (OrderItems $data) {
                    if ($data->options) {
                        $str = '';
                        foreach ($data->getItemOptions() as $option) {
                            $str .= Html::a($option->title, ['product-options/view', 'id' => $option->id]);
                            $str .= " (+{$option->getPrice()}р)";
                            $str .= '<br>';
                        }
                        return $str;
                    }
                    return null;
                },
            ],
            [
                'label' => 'Количество',
                'format' => 'raw',
                'value' => function (OrderItems $data) {
                    return $data->qty_item;
                },
            ],
            [
                'label' => 'Цена',
                'format' => 'raw',
                'value' => function (OrderItems $data) {
                    return $data->price_item;
                },
            ],
            [
                'label' => 'Сумма',
                'format' => 'raw',
                'value' => function (OrderItems $data) {
                    return $data->price_item * $data->qty_item;
                },
            ],
        ],
    ]); ?>
</div>

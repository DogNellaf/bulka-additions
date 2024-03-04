<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\entities\User;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <p>
        <?//= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id',
                    'username',
                    'email:email',
                    'phone',
                    [
                        'attribute' => 'business',
                        'format' => 'raw',
                        'value' => function ($data) {
                            if ($data->business) {
                                return Html::a('Бизнес-клиент', Url::to(['business', 'id' => $data->id]), ['class' => 'btn btn-success btn-raised']);
                            } else {
                                return Html::a('Клиент', Url::to(['business', 'id' => $data->id]), ['class' => 'btn btn-info btn-raised']);
                            }
                        },
                        'options' => ['style' => 'width: 150px; max-width: 150px;'],
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}{update}',
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>

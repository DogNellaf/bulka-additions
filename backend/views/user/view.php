<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use common\entities\User;

/* @var $this yii\web\View */
/* @var $model common\entities\User */

$this->title = $model->email;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>
    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'username',
                    'company_name',
                    'email:email',
                    'phone',
                    'inn',
                    'kpp',
                    //'status',
                    'created_at:datetime',
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
                    'wholesale',
                ],
            ]) ?>

        </div>
    </div>
</div>

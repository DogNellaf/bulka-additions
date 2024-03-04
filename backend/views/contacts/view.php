<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\entities\Contacts;

/* @var $this yii\web\View */
/* @var $model common\entities\Contacts */

$this->title = $model::VARIANTS[$model->type];
$this->params['breadcrumbs'][] = ['label' => 'Контакты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contacts-view">

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы точно хотите удалить эту запись?',
                'method' => 'post',
            ],
        ]) ?>
        <?php if ($model->status) {
            echo Html::a('<span class="glyphicon glyphicon-ok"></span> Отображать', ['status', 'id' => $model->id], ['class' => 'btn btn-success btn-raised pull-right']);
        } else {
            echo Html::a('<span class="glyphicon glyphicon-remove"></span> Скрывать', ['status', 'id' => $model->id], ['class' => 'btn btn-danger btn-raised pull-right']);
        }; ?>
    </p>
    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'attribute' => 'sort',
                    ],
                    [
                        'attribute' => 'type',
                        'value' => function (Contacts $data) {
                            return $data::VARIANTS[$data->type];
                        }
                    ],
                    [
                        'attribute' => 'value',
                        'format' => 'raw'
                    ],
                ],
            ]) ?>

        </div>
    </div>
</div>

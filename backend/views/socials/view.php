<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\entities\Socials;

/* @var $this yii\web\View */
/* @var $model common\entities\Socials */

$this->title = $model::VARIANTS[$model->icon];
$this->params['breadcrumbs'][] = ['label' => 'Соцсети', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="socials-view">

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php if ($model->status) {
            echo Html::a('<span class="glyphicon glyphicon-ok"></span> Отображать', ['status', 'id' => $model->id], ['class' => 'btn btn-success btn-raised pull-right']);
        } else {
            echo Html::a('<span class="glyphicon glyphicon-remove"></span> Скрывать', ['status', 'id' => $model->id], ['class' => 'btn btn-danger btn-raised pull-right']);
        }; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'ord',
                'value' => function (Socials $model) {
                    return $model->ord + 1;
                }
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
        ],
    ]) ?>

</div>


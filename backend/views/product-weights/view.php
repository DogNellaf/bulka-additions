<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\entities\ProductWeights;

/* @var $this yii\web\View */
/* @var $model common\entities\ProductWeights */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => $model->product->title, 'url' => ['index', 'slug' => $model->product->slug]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-weights-view">

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы точно хотите удалить эту запись?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Продукт', ['/products/view', 'id' => $model->product_id], ['class' => 'btn btn-info', 'data-pjax' => 0]) ?>
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
                    'id',
                    'id_1c',
                    'title',
                    'price',
                    'business_price',
                    'min_quantity',
                    'balance',
                    'sort',
                    'status',
                ],
            ]) ?>

        </div>
    </div>
</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model \common\entities\Products */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => $model->category->title, 'url' => ['index', 'slug' => $model->category->slug]];
$this->params['breadcrumbs'][] = $this->title;

$relProducts = \yii\helpers\ArrayHelper::map(\common\entities\Products::find()->asArray()->all(), 'id', 'title');
?>
<div class="products-view">

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы точно хотите удалить эту запись?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Вес/цена', ['/product-weights', 'slug' => $model->slug], ['class' => 'btn btn-info', 'data-pjax' => 0]) ?>
        <?//= Html::a('Опции', ['/product-options', 'id' => $model->id], ['class' => 'btn btn-info', 'data-pjax' => 0]) ?>

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
                    //'id_1c',
                    'sku',
                    'title',
                    //'price',
                    [
                        'attribute' => 'description',
                        'format' => 'raw'
                    ],
                    'proteins',
                    'fats',
                    'carbohydrates',
                    'kcal',
                    'min_delivery_days',
                    [
                        'label' => 'Изображение',
                        'format' => 'raw',
                        'value' => function ($data) {
                            if ($data->image_name) {

                                return Html::img($data->image, [
                                    'alt' => 'yii2 - картинка в gridview',
                                    'style' => 'height:100px;'
                                ]);
                            }
                            return false;
                        },
                    ],
                    [
                        'attribute' => 'rel_products',
                        'format' => 'raw',
                        'value' => function (\common\entities\Products $data) use ($relProducts) {
                            if ($data->rel_products) {
                                $dataRelProducts = \yii\helpers\ArrayHelper::toArray(\yii\helpers\Json::decode($data->rel_products));
                                $cats = '';
                                foreach ($dataRelProducts as $product) {
                                    $cats .= html::a($relProducts[$product], ['/products/view', 'id' => $product]) . '<br>';
                                }
                                return $cats;
                            }
                            return false;
                        },
                    ],
                ],
            ]) ?>

        </div>
    </div>
</div>

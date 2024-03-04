<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model \common\entities\MenuProducts */

$this->title = $model->title_ru;
$this->params['breadcrumbs'][] = ['label' => $model->category->title_ru, 'url' => ['index', 'slug' => $model->category->slug]];
$this->params['breadcrumbs'][] = $this->title;


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
                    'price',
                    [
                        'attribute' => 'description',
                        'format' => 'raw'
                    ],
                    'title_ru',
                    'title_desc_ru',
                    'additional_ru',
                    'link_ru',
                    'href_ru',
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
                        'label' => 'Иконка названия продукта',
                        'format' => 'raw',
                        'value' => function ($data) {
                            if ($data->iconTitle->image_name) {
                                return Html::img('/files/menu_icons/' . $data->iconTitle->image_name, [
                                    'alt' => 'yii2 - картинка в gridview',
                                    'style' => 'height:40px;'
                                ]);
                            }
                            return false;
                        },
                    ],


                    [
                        'label' => 'Иконка описание продукта',
                        'format' => 'raw',
                        'value' => function ($data) {
                            if ($data->iconDesc->image_name) {

                                return Html::img('/files/menu_icons/' . $data->iconDesc->image_name, [
                                    'alt' => 'yii2 - картинка в gridview',
                                    'style' => 'height:40px;'
                                ]);
                            }
                            return false;
                        },
                    ],


                    [
                        'label' => 'Иконка ссылки продукта',
                        'format' => 'raw',
                        'value' => function ($data) {
                            if ($data->iconLink->image_name) {

                                return Html::img('/files/menu_icons/' . $data->iconLink->image_name, [
                                    'alt' => 'yii2 - картинка в gridview',
                                    'style' => 'height:40px;'
                                ]);
                            }
                            return false;
                        },
                    ],
                ],
            ]) ?>

        </div>
    </div>
</div>

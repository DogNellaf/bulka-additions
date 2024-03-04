<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\entities\Modules;

/* @var $this yii\web\View */
/* @var $model common\entities\Modules */

$this->title = $model->title ?: 'Модуль';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modules-view">

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>
    <div class="box">
        <div class="box-body">
            <?php
            $attributes = [];
            if ($model->image_name) {
                $attributes[] = [
                    'label' => 'Изображение',
                    'format' => 'raw',
                    'value' => function (Modules $data) {
                        if ($data->image_name) {
                            return Html::img($data->image, [
                                'alt' => 'yii2 - картинка в gridview',
                                'style' => 'width:600px;'
                            ]);
                        }
                        return null;
                    },
                ];
                $attributes[] = 'alt';
            }
            if ($model->image_name_2) {
                $attributes[] = [
                    'label' => 'Изображение 2',
                    'format' => 'raw',
                    'value' => function (Modules $data) {
                        if ($data->image_name_2) {
                            return Html::img($data->image2, [
                                'alt' => 'yii2 - картинка в gridview',
                                'style' => 'width:600px;'
                            ]);
                        }
                        return null;
                    },
                ];
                $attributes[] = 'alt2';
            }
            if ($model->title) {
                $attributes[] = 'title';
            }

            if ($model->description) {
                $attributes[] = [
                    'attribute' => 'description',
                    'format' => 'raw'
                ];
            }
            if ($model->html) {
                $attributes[] = [
                    'attribute' => 'html',
                    'format' => 'raw'
                ];
            }
            if ($model->link) {
                $attributes[] = 'link';
            }
            if ($model->min_order_sum) {
                $attributes[] = 'min_order_sum';
            }
            if ($model->min_free_delivery_sum) {
                $attributes[] = 'min_free_delivery_sum';
            }
            if ($model->min_free_delivery_sum_outer_mkad) {
                $attributes[] = 'min_free_delivery_sum_outer_mkad';
            }
            ?>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => $attributes,
            ]) ?>
        </div>
    </div>
</div>

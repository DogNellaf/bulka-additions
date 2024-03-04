<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\entities\Stories;

/* @var $this yii\web\View */
/* @var $model common\entities\Stories */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Истории', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stories-view">

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
                    'id',
                    'title',
                    'html:html',
                    [
                        'label' => 'Изображение',
                        'format' => 'raw',
                        'value' => function ($data) {
                            if ($data->image_name) {

                                return Html::img($data->image, [
                                    'alt' => 'yii2 - картинка в gridview',
                                    'style' => 'width:300px;'
                                ]);
                            }
                            return false;
                        },
                    ],
                    [
                        'label' => 'Видео',
                        'format' => 'raw',
                        'value' => function ($data) {
                            if ($data->video_name) {

                                return Html::label($data->image, []);
                            }
                            return false;
                        },
                    ],
                    'slug',
                    'sort',
                    'status',
                    'meta_title',
                    'meta_description:ntext',
                    'meta_keywords',
                ],
            ]) ?>

        </div>
    </div>
</div>

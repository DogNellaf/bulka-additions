<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\entities\Galleries;

/* @var $this yii\web\View */
/* @var $model common\entities\BonusDocs */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'документы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="galleries-view">

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы точно хотите удалить эту запись?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Тексты', ['/restaurant-texts', 'id' => $model->id], ['class' => 'btn btn-info', 'data-pjax' => 0]) ?>
        <?= Html::a('Меню', ['/restaurant-menus', 'id' => $model->id], ['class' => 'btn btn-info', 'data-pjax' => 0]) ?>

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
                    'alt',
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

    <?php if ($model->galleryAttachments): ?>
        <h2>Галерея</h2>
        <div class='gallery'>
            <?php foreach ($model->galleryAttachments as $attachment): ?>
                <div class="galImg" style="background-image: url(<?= $attachment->getUrl(); ?>);"></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

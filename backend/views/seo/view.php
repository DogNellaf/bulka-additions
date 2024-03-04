<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\entities\Seo */

$this->title = 'SEO страницы ' . $model->page;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="seo-view">
    <p>
        <?= Html::a('Изменить', ['update', 'page' => $model->page], ['class' => 'btn btn-primary']) ?>
    </p>
    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'meta_title',
                    'meta_description',
                    'meta_keywords',
                ],
            ]) ?>
        </div>
    </div>
</div>

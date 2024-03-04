<?php
/* @var $this yii\web\View */
/* @var $model common\entities\MainSlider */

$this->title = 'Изменить: ' . $model->title;

$this->params['breadcrumbs'][] = ['label' => 'Главный Слайдер', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="main-slider-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

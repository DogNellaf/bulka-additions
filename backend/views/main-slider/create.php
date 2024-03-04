<?php
/* @var $this yii\web\View */
/* @var $model common\entities\MainSlider */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Главный Слайдер', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="main-slider-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

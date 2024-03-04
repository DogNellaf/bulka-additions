<?php
/* @var $this yii\web\View */
/* @var $model common\entities\Restaurants */

$this->title = 'Изменить: ' . $model->title;

$this->params['breadcrumbs'][] = ['label' => 'Рестораны', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="restaurants-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php
/* @var $this yii\web\View */
/* @var $model common\entities\Stories */

$this->title = 'Изменить: ' . $model->title;

$this->params['breadcrumbs'][] = ['label' => 'Истории', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="stories-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

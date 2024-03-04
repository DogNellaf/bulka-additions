<?php

/* @var $this yii\web\View */
/* @var $model common\entities\Socials */

$this->title = 'Изменить: ' . $model::VARIANTS[$model->icon];
$this->params['breadcrumbs'][] = ['label' => 'Соцсети', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model::VARIANTS[$model->icon], 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="socials-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

/* @var $this yii\web\View */
/* @var $model common\entities\Contacts */

$this->title = 'Изменить: ' . $model::VARIANTS[$model->type];
$this->params['breadcrumbs'][] = ['label' => 'Контакты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model::VARIANTS[$model->type], 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="contacts-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

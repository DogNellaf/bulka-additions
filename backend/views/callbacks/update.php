<?php
/* @var $this yii\web\View */
/* @var $model common\entities\Callbacks */

$this->title = 'Изменить: ' . $model->name;

$this->params['breadcrumbs'][] = ['label' => 'Сообщения', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="callbacks-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

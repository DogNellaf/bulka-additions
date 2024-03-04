<?php
/* @var $this yii\web\View */
/* @var $model common\entities\Reserves */

$this->title = 'Изменить: ' . $model->name;

$this->params['breadcrumbs'][] = ['label' => 'Резерв', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="reserves-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

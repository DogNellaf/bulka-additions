<?php
/* @var $this yii\web\View */
/* @var $model common\entities\BonusDocs */

$this->title = 'Изменить: ' . $model->title;

$this->params['breadcrumbs'][] = ['label' => 'Галерея', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="galleries-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

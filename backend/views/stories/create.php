<?php
/* @var $this yii\web\View */
/* @var $model common\entities\Stories */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Истории', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stories-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

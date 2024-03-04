<?php
/* @var $this yii\web\View */
/* @var $model common\entities\Callbacks */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Сообщения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="callbacks-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

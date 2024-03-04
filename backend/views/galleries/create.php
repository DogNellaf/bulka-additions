<?php
/* @var $this yii\web\View */
/* @var $model common\entities\Galleries */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Галерея', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="galleries-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

/* @var $this yii\web\View */
/* @var $model common\entities\Socials */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Соцсети', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="socials-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
